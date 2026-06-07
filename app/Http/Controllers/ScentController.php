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
        $activePromotions = \App\Models\Promotion::where('is_active', true)
            ->where(function($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->get();

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
                $variant = $product->variants->first();
                $originalPrice = $variant ? $variant->price : 0;
                $price = $originalPrice;
                $discountBadge = null;

                if ($activePromotions->isNotEmpty() && $variant) {
                    foreach ($activePromotions as $promo) {
                        if ($promo->applies_to_all || ($promo->product_id && $promo->product_id == $product->id)) {
                            if ($promo->discount_type === 'percent') {
                                $price = $originalPrice - ($originalPrice * ($promo->discount_value / 100));
                                $discountBadge = (float) $promo->discount_value . '% OFF';
                            } else {
                                $price = $originalPrice - $promo->discount_value;
                                $discountBadge = '- Rp ' . number_format($promo->discount_value, 0, ',', '.');
                            }
                            break;
                        }
                    }
                }

                $result[$mood] = [
                    'id' => $product->id,
                    'variant_id' => $variant ? $variant->id : null,
                    'badge' => ucfirst($mood) . ' Recommendation',
                    'title' => $product->name,
                    'desc' => strip_tags($product->description ?? ''),
                    'original_price' => $originalPrice > $price ? 'Rp ' . number_format($originalPrice, 0, ',', '.') : null,
                    'price' => $variant ? 'Rp ' . number_format($price, 0, ',', '.') : 'Price Unavailable',
                    'discount_badge' => $discountBadge,
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
