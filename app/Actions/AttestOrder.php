<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Events\OrderAttested;
use App\Models\{Order, User};

class AttestOrder
{
    use AsAction;

    public function handle(Order $order, User $user, string $signature): Order
    {
        $item = $order->items()->make(['role' => 'xxx']);
        $item->user()->associate($user);
        $item->signature = $signature;
        $item->signed_at = now();
        $item->save();
        OrderAttested::dispatch($order);

        return $order;
    }
}
