<?php

namespace App\Actions;

use App\Exceptions\MinimumAttestsNotMet;
use Bavix\Wallet\Internal\Exceptions\ExceptionInterface;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Events\OrderTransacted;
use App\Models\{Order, User};

class TransactOrder
{
    use AsAction;

    const MINIMUM_ATTESTS = 1;

    /**
     * @throws ExceptionInterface
     * @throws MinimumAttestsNotMet
     */
    public function handle(Order $order, User $user, string $signature, bool $force = false): Order
    {
        if ($force || ($order->items->count() >= self::MINIMUM_ATTESTS)) {
            $order->user()->associate($user);
            $order->signature = $signature;
            $order->signed_at = now();
            $order->save();
            $order->project->pay($order->trip);
            OrderTransacted::dispatch($order);

            return $order;
        }

        throw new MinimumAttestsNotMet;
    }
}
