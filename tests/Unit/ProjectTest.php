<?php

use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use Database\Seeders\UserSeeder;
use App\Models\{Project, User};

uses(RefreshDatabase::class, WithFaker::class);

beforeEach(function () {
    $this->seed(UserSeeder::class);
});

test('project has attributes', function () {
    $project = Project::factory()->create();
    expect($project->code)->toBeString();
    expect($project->name)->toBeString();
});

test('project can be persisted', function () {
    $code = fake()->word();
    $name = fake()->sentence();
    $project = Project::create([
        'code' => $code,
        'name' => $name
    ]);
    expect($project->code)->toBe($code);
    expect($project->name)->toBe($name);
});

test('user can transfer amount to project', function () {
    $user = User::first();
    $project = Project::factory()->create();
    expect($project->balanceFloat)->toBe('0.00');
    $user->transferFloat($project, 100000);
    expect($user->balanceFloat)->toBe('900000.00');
    expect($project->balanceFloat)->toBe('100000.00');
});
