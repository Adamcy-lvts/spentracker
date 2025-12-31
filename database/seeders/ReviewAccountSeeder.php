<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ReviewAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'review@spenttracker.live'],
            [
                'name' => 'Review Account',
                'password' => Hash::make('Review@12345'),
                'email_verified_at' => now(),
            ]
        );
    }
}
