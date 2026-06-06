<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Product;
use App\Models\Wishlist;
use App\Mail\WishlistDiscountMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotifyUsersOfProductDiscountJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $product;

    /**
     * Create a new job instance.
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Running NotifyUsersOfProductDiscountJob for product: {$this->product->name}");
        
        $wishlists = Wishlist::with('user')->where('product_id', $this->product->id)->get();
        
        foreach ($wishlists as $wishlist) {
            if ($wishlist->user && $wishlist->user->email) {
                Mail::to($wishlist->user->email)->send(new WishlistDiscountMail($this->product, $wishlist->user));
            }
        }
    }
}
