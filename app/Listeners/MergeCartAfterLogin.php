<?php

namespace App\Listeners;

use App\Models\Cart;
use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MergeCartAfterLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event)
    {
        $sessionCart = session()->get('cart', []);

        foreach ($sessionCart as $productId => $details) {
            $item = Cart::firstOrNew([
                'user_id' => $event->user->id,
                'product_id' => $productId,
            ]);

            $item->quantity += $details['quantity'];
            $item->save();
        }

        session()->forget('cart');
    }
}
