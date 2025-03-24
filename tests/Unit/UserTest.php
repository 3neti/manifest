<?php

use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use Database\Seeders\UserSeeder;
use App\Models\User;

uses(RefreshDatabase::class, WithFaker::class);

beforeEach(function () {
   $this->seed(UserSeeder::class);
});

test('first user has balance of 1,000,000.00', function () {
    $user = User::first();
    expect($user->balanceFloat)->toBe('1000000.00');
});
