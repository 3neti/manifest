<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $system = User::create([
            'name' => 'System User',
            'email' => 'lester@hurtado.ph',
            'password' => bcrypt('password'),
        ]);
        $system->depositFloat(1000000);
    }
}
