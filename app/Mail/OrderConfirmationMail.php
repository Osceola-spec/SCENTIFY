<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    // Membuat variabel publik agar bisa dibaca langsung di dalam file Blade (tampilan email)
    public $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Mengatur Judul / Subjek Email
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Scentify - Order Payment Confirmation #' . $this->order->order_number,
        );
    }

    /**
     * Mengatur File Tampilan (Template) HTML Email
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order_confirmation', // Ini mengarah ke folder resources/views/emails/order_confirmation.blade.php
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}