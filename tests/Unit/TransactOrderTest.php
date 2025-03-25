<?php

use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use App\Models\{Order, Project, Trip, User};
use Illuminate\Support\Facades\Event;
use Database\Seeders\UserSeeder;
use App\Events\OrderTransacted;
use App\Actions\TransactOrder;

uses(RefreshDatabase::class, WithFaker::class);

beforeEach(function () {
    Event::fake([OrderTransacted::class]);
    $this->seed(UserSeeder::class);
});

dataset('system', function() {
    return [
        [fn() => User::first()]
    ];
});

test('transact order works', function (User $system) {
    $project = Project::factory()->create();
    $system->transferFloat($project, 100000.0);
    expect((float) $project->balanceFloat)->toBe(100000.0);
    $order = Order::factory()
        ->forUser()
        ->for(Trip::factory()->state(['amount' => 1000.0]), 'trip')
        ->for($project, 'project')
        ->create(['signature' => null, 'signed_at' => null]);
    expect($user = $order->user)->toBeInstanceOf(User::class);
    expect($trip = $order->trip)->toBeInstanceOf(Trip::class);
    expect($trip->amount->getAmount()->toFloat())->toBe(1000.0);
    expect($order->signature)->toBeNull();
    expect((float) $project->balanceFloat)->toBe(100000.0);
    $order = TransactOrder::run($order, $user, 'signature here', true);
    expect($order->signature)->toBeString();
    expect($order->signed_at)->toBeInstanceOf(DateTime::class);
    expect((float) $project->balanceFloat)->toBe(100000.0 - 1000.0);
    Event::assertDispatched(OrderTransacted::class);
})->with('system');
