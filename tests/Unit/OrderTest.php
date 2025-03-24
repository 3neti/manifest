<?php

use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use App\Models\{Order, OrderItems,  Project, Trip, User};
use Illuminate\Database\Eloquent\Collection;
use Database\Seeders\UserSeeder;

uses(RefreshDatabase::class, WithFaker::class);

beforeEach(function () {
    $this->seed(UserSeeder::class);
});

test('order has attributes', function () {
    $order = Order::factory()->forUser()->forProject()->forTrip()->create();
    expect($order->requested_on)->toBeInstanceOf(DateTime::class);
    expect($order->user)->toBeInstanceOf(User::class);
    expect($order->project)->toBeInstanceOf(Project::class);
    expect($order->trip)->toBeInstanceOf(Trip::class);
    expect($order->remarks)->toBeString();
    expect($order->signature)->toBeString();
    expect($order->signed_at)->toBeInstanceOf(DateTime::class);
});

test('order has items', function () {
    $order = Order::factory()
        ->forProject()
        ->forTrip()
        ->hasItems(3, fn (array $attributes, Order $order) => [
            'user_id' => User::factory(), // 👈 assign user here
        ])
        ->create();
    expect($order->items)->toBeInstanceOf(Collection::class);
    $item = $order->items->first();
    expect($item)->toBeInstanceOf(OrderItems::class);
    expect($item->role)->toBeString();
    expect($item->user)->toBeInstanceOf(User::class);
    expect($item->signature)->toBeString();
    expect($item->signed_at)->toBeInstanceOf(DateTime::class);
});
