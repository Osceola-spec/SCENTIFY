<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductUpdated implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public $product;

    public function __construct($product)
    {
        $this->product = $product;
    }

    public function broadcastOn()
    {
        return new Channel('scentify-live');
    }

    public function broadcastAs()
    {
        return 'product.updated';
    }
}
