<?php


use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use App\Actions\{AttestOrder, TransactOrder};
use App\Models\{Order, Project, Trip, User};
use Database\Seeders\UserSeeder;

uses(RefreshDatabase::class, WithFaker::class);

beforeEach(function () {
    $this->seed(UserSeeder::class);
});

dataset('system', function() {
   return [
       [fn() => User::first()]
   ];
});

test('process works', function (User $system) {
    expect($system->name)->toBe('System User');
    expect($system->email)->toBe('lester@hurtado.ph');
    expect((float) $system->balanceFloat)->toBe(1000000.0);

    $project1 = Project::factory()->create(['code' => 'ABC']);
    $project2 = Project::factory()->create(['code' => 'DEF']);
    $project3 = Project::factory()->create(['code' => 'GHI']);
    $project4 = Project::factory()->create(['code' => 'JKL']);

    expect((float) $project1->balanceFloat)->toBe(0.0);
    expect((float) $project2->balanceFloat)->toBe(0.0);
    expect((float) $project3->balanceFloat)->toBe(0.0);
    expect((float) $project4->balanceFloat)->toBe(0.0);

    $system->transferFloat($project1, 100000.0);
    $system->transferFloat($project2, 100000.0);
    $system->transferFloat($project3, 100000.0);
    $system->transferFloat($project4, 100000.0);

    expect((float) $project1->balanceFloat)->toBe(100000.0);
    expect((float) $project2->balanceFloat)->toBe(100000.0);
    expect((float) $project3->balanceFloat)->toBe(100000.0);
    expect((float) $project4->balanceFloat)->toBe(100000.0);

    $trip1 = Trip::factory()->create(['amount' => 1000.0]);
    $trip2 = Trip::factory()->create(['amount' => 2000.0]);
    $trip3 = Trip::factory()->create(['amount' => 3000.0]);
    $trip4 = Trip::factory()->create(['amount' => 4000.0]);

    expect($trip1->amount->getAmount()->toFloat())->toBe(1000.0);
    expect($trip2->amount->getAmount()->toFloat())->toBe(2000.0);
    expect($trip3->amount->getAmount()->toFloat())->toBe(3000.0);
    expect($trip4->amount->getAmount()->toFloat())->toBe(4000.0);

    $admin1 = User::factory()->create();
    $order1 = Order::factory()
        ->for($project1, 'project')
        ->for($trip1, 'trip')
        ->for($admin1, 'user')
        ->create();
    expect((float) $project1->balanceFloat)->toBe(100000.0);

    $signatory1 = User::factory()->create();
    $signatory2 = User::factory()->create();

    AttestOrder::run($order1, $signatory1, $this->faker->text());
    AttestOrder::run($order1, $signatory2, $this->faker->text());

    TransactOrder::run($order1, $admin1, $this->faker->text());
    expect((float) $project1->balanceFloat)->toBe(100000.0 - 1000.0);
})->with('system');
