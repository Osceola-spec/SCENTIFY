<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promotion;
use App\Models\Product;
use App\Events\PromotionEvent;

class AdminPromotionController extends Controller
{
    public function index()
    {
        // Auto-disable expired promos
        Promotion::where('is_active', true)
            ->whereNotNull('ends_at')
            ->where('ends_at', '<', now())
            ->update(['is_active' => false]);

        $promotions = Promotion::latest()->paginate(20);
        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('admin.promotions.create', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:0',
            // checkboxes send 'on' which Laravel boolean validator doesn't accept, so validate as nullable and coerce below
            'applies_to_all' => 'nullable',
            'product_id' => 'nullable|exists:products,id',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'nullable',
        ]);

        $data['applies_to_all'] = $request->has('applies_to_all');
        $data['is_active'] = $request->has('is_active');

        if ($data['is_active']) {
            $startsAt = $data['starts_at'] ?? null;
            $endsAt = $data['ends_at'] ?? null;

            $overlap = Promotion::where('is_active', true)
                ->where(function($q) use ($startsAt, $endsAt) {
                    $q->where(function($q2) use ($endsAt) {
                        if ($endsAt) {
                            $q2->whereNull('starts_at')->orWhere('starts_at', '<=', $endsAt);
                        }
                    })->where(function($q2) use ($startsAt) {
                        if ($startsAt) {
                            $q2->whereNull('ends_at')->orWhere('ends_at', '>=', $startsAt);
                        }
                    });
                })->exists();

            if ($overlap) {
                return back()->withErrors(['starts_at' => 'Promo schedule overlaps with another active promotion.'])->withInput();
            }
        }

        $promotion = Promotion::create($data);
        event(new PromotionEvent($promotion, 'created'));
        
        if ($promotion->is_active) {
            \App\Jobs\NotifyUsersOfPromoJob::dispatch($promotion);
        }

        return redirect()->route('admin.promotions.index')->with('success', 'Promo successfully created');
    }

    public function edit(Promotion $promotion)
    {
        $products = Product::orderBy('name')->get();
        return view('admin.promotions.edit', compact('promotion','products'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:0',
            'applies_to_all' => 'nullable',
            'product_id' => 'nullable|exists:products,id',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'nullable',
        ]);

        $data['applies_to_all'] = $request->has('applies_to_all');
        $data['is_active'] = $request->has('is_active');

        if ($data['is_active']) {
            $startsAt = $data['starts_at'] ?? null;
            $endsAt = $data['ends_at'] ?? null;

            $overlap = Promotion::where('is_active', true)
                ->where('id', '!=', $promotion->id)
                ->where(function($q) use ($startsAt, $endsAt) {
                    $q->where(function($q2) use ($endsAt) {
                        if ($endsAt) {
                            $q2->whereNull('starts_at')->orWhere('starts_at', '<=', $endsAt);
                        }
                    })->where(function($q2) use ($startsAt) {
                        if ($startsAt) {
                            $q2->whereNull('ends_at')->orWhere('ends_at', '>=', $startsAt);
                        }
                    });
                })->exists();

            if ($overlap) {
                return back()->withErrors(['starts_at' => 'Promo schedule overlaps with another active promotion.'])->withInput();
            }
        }

        $promotion->update($data);
        event(new PromotionEvent($promotion, 'updated'));
        return redirect()->route('admin.promotions.index')->with('success', 'Promo successfully updated');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        event(new PromotionEvent($promotion, 'deleted'));
        return redirect()->route('admin.promotions.index')->with('success', 'Promo deleted');
    }
}
