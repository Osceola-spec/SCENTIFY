<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Product;

class ProductAdded implements ShouldBroadcastNow
{
    public $product;

    public function __construct(Product $product)
    {
        $product->load(['brand', 'variants']);

        $this->product = $product;
    }

    public function broadcastOn()
    {
        // Channel publik yang didengarkan oleh semua pengunjung web
        return new \Illuminate\Broadcasting\Channel('scentify-live');
    }

    public function broadcastAs()
    {
        return 'product.added';
    }
}
