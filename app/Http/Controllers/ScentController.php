<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ScentController extends Controller
{
    /**
     * Return scent recommendations grouped by mood/category.
     * Tries to find products by category keywords; falls back to first products.
     */
    public function recommendations(Request $request)
    {
        $moods = ['woody', 'floral', 'citrus', 'oriental'];
        $result = [];

        foreach ($moods as $mood) {
            $product = Product::whereRaw('LOWER(category) LIKE ?', ["%{$mood}%"])
                ->orWhereHas('notes', function($q) use ($mood) {
                    $q->whereRaw('LOWER(name) LIKE ?', ["%{$mood}%"]);
                })
                ->with(['brand','variants','notes'])
                ->first();

            if (!$product) {
                $product = Product::with(['brand','variants','notes'])->first();
            }

            if ($product) {
                $result[$mood] = [
                    'id' => $product->id,
                    'badge' => ucfirst($mood) . ' Recommendation',
                    'title' => $product->name,
                    'desc' => strip_tags($product->description ?? ''),
                    'price' => $product->variants->first() ? 'Rp ' . number_format($product->variants->first()->price,0,',','.') : 'Price Unavailable',
                    'color' => $this->moodColor($mood),
                    'top' => $product->notes->pluck('name')->slice(0,2)->join(', '),
                    'heart' => $product->notes->pluck('name')->slice(2,2)->join(', '),
                    'base' => $product->notes->pluck('name')->slice(4,2)->join(', '),
                    'product_slug' => $product->slug ?? null,
                ];
            }
        }

        return response()->json($result);
    }

    private function moodColor($mood)
    {
        return match($mood) {
            'woody' => '#f59e0b',
            'floral' => '#ec4899',
            'citrus' => '#10b981',
            'oriental' => '#8b5cf6',
            default => '#6b7280'
        };
    }
}
