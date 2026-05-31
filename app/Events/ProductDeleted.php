<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductDeleted implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public $productId;

    public function __construct($productId)
    {
        $this->productId = $productId;
    }

    public function broadcastOn()
    {
        return new Channel('scentify-live');
    }

    public function broadcastAs()
    {
        return 'product.deleted';
    }
}
