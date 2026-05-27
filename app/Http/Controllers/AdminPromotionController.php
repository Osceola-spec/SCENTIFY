<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promotion;
use App\Models\Product;

class AdminPromotionController extends Controller
{
    public function index()
    {
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

        Promotion::create($data);
        return redirect()->route('admin.promotions.index')->with('success', 'Promo berhasil dibuat');
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

        $promotion->update($data);
        return redirect()->route('admin.promotions.index')->with('success', 'Promo berhasil diperbarui');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return redirect()->route('admin.promotions.index')->with('success', 'Promo dihapus');
    }
}
