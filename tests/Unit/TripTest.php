<?php

use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use App\Models\{Project, Trip, User};
use Bavix\Wallet\Models\Transfer;
use Database\Seeders\UserSeeder;

uses(RefreshDatabase::class, WithFaker::class);

beforeEach(function () {
    $this->seed(UserSeeder::class);
});

test('trip has attributes', function () {
    $trip = Trip::factory()->create();
    expect($trip->code)->toBeString();
    expect($trip->name)->toBeString();
});

test('trip can be persisted', function () {
    $code = fake()->word();
    $name = fake()->sentence();
    $amount = fake()->numberBetween(1000,10000000);
    $trip = Trip::create([
        'code' => $code,
        'name' => $name,
        'amount' => $amount
    ]);
    expect($trip->code)->toBe($code);
    expect($trip->name)->toBe($name);
});

test('trip can be booked', function () {
    $user = User::first();
    $project = Project::factory()->create();
    $user->transferFloat($project, 100000.0);
    $trip = Trip::factory()->create(['amount' => 1000.00]);//major unit
    expect($trip->amount->getAmount()->toFloat())->toBe(1000.00);
    expect((float) $trip->balanceFloat)->toBe(0.00);
    expect($trip->getAmountProduct($project))->toBe(1000 * 100);//$this->getRawOriginal('amount')
    $transfer = $project->pay($trip);
    expect($transfer)->toBeInstanceOf(Transfer::class);
    expect((float) $project->balanceFloat)->toBe(100000.0 - 1000);
    expect((float) $trip->balanceFloat)->toBe(1000.00);
});
