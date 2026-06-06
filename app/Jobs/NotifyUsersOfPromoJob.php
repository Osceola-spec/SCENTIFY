<?php

namespace App\Jobs;

use App\Mail\PromoNotificationMail;
use App\Models\Promotion;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotifyUsersOfPromoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $promotion;

    /**
     * Create a new job instance.
     */
    public function __construct(Promotion $promotion)
    {
        $this->promotion = $promotion;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Running NotifyUsersOfPromoJob for promotion: {$this->promotion->title}");
        
        if ($this->promotion->applies_to_all) {
            $users = User::all();
            foreach ($users as $user) {
                if ($user->email) {
                    Mail::to($user->email)->send(new PromoNotificationMail($this->promotion, $user, false));
                }
            }
        } else {
            $notifiedUserIds = [];
            
            if ($this->promotion->product_id) {
                // Find users who have this product in their wishlist
                $wishlists = Wishlist::with('user')->where('product_id', $this->promotion->product_id)->get();
                
                foreach ($wishlists as $wishlist) {
                    if ($wishlist->user && $wishlist->user->email) {
                        Mail::to($wishlist->user->email)->send(new PromoNotificationMail($this->promotion, $wishlist->user, true));
                        $notifiedUserIds[] = $wishlist->user->id;
                    }
                }
            }

            // Notify remaining users generally
            $users = User::whereNotIn('id', $notifiedUserIds)->get();
            foreach ($users as $user) {
                if ($user->email) {
                    Mail::to($user->email)->send(new PromoNotificationMail($this->promotion, $user, false));
                }
            }
        }
    }
}
