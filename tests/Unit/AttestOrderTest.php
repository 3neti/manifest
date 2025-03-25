<?php

use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use App\Models\{Order, Project, Trip, User};
use Illuminate\Support\Facades\Event;
use Database\Seeders\UserSeeder;
use App\Events\OrderAttested;
use App\Actions\AttestOrder;

uses(RefreshDatabase::class, WithFaker::class);

beforeEach(function () {
    Event::fake([OrderAttested::class]);
    $this->seed(UserSeeder::class);
});

dataset('system', function() {
    return [
        [fn() => User::first()]
    ];
});

test('transact order works', function (User $system) {
    $order = Order::factory()
        ->forUser()
        ->forTrip()
        ->forProject()
        ->create();
    $order->refresh();
    [$user1, $user2] = User::factory(2)->create();
    expect($order->items)->toHaveCount(0);
    $order = AttestOrder::run($order, $user1, 'user1 signature here');
    $order->refresh();
    expect($order->items)->toHaveCount(1);
    $order = AttestOrder::run($order, $user2, 'user2 signature here');
    $order->refresh();
    expect($order->items)->toHaveCount(2);
    [$item1, $item2] = $order->items;
    expect($item1->user->is($user1))->toBeTrue();
    expect($item1->signature)->toBe('user1 signature here');
    expect($item1->signed_at)->toBeInstanceOf(DateTime::class);
    expect($item2->user->is($user2))->toBeTrue();
    expect($item2->signature)->toBe('user2 signature here');
    expect($item2->signed_at)->toBeInstanceOf(DateTime::class);
    Event::assertDispatched(OrderAttested::class, function (OrderAttested $event) use ($order) {
        return $event->order->is($order);
    });
})->with('system');
