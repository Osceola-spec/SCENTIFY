<?php

namespace App\Mail;

use App\Models\Promotion;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PromoNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $promotion;
    public $user;
    public $isWishlistMatch;

    /**
     * Create a new message instance.
     */
    public function __construct(Promotion $promotion, User $user, bool $isWishlistMatch = false)
    {
        $this->promotion = $promotion;
        $this->user = $user;
        $this->isWishlistMatch = $isWishlistMatch;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->isWishlistMatch 
            ? 'A product in your wishlist is on sale! ✨' 
            : 'Special Promo from Scentify! 🎉';

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.promo_notification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
