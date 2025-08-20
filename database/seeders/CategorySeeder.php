<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Food & Dining', 'icon' => 'utensils', 'color' => '#10B981', 'description' => 'Restaurants, groceries, snacks'],
            ['name' => 'Transportation', 'icon' => 'car', 'color' => '#3B82F6', 'description' => 'Gas, public transport, taxi, car maintenance'],
            ['name' => 'Shopping', 'icon' => 'shopping-bag', 'color' => '#8B5CF6', 'description' => 'Clothing, electronics, general shopping'],
            ['name' => 'Entertainment', 'icon' => 'film', 'color' => '#F59E0B', 'description' => 'Movies, games, concerts, hobbies'],
            ['name' => 'Bills & Utilities', 'icon' => 'receipt', 'color' => '#EF4444', 'description' => 'Electricity, water, internet, phone'],
            ['name' => 'Healthcare', 'icon' => 'heart', 'color' => '#EC4899', 'description' => 'Doctor visits, medicine, insurance'],
            ['name' => 'Education', 'icon' => 'graduation-cap', 'color' => '#06B6D4', 'description' => 'Courses, books, training'],
            ['name' => 'Travel', 'icon' => 'plane', 'color' => '#84CC16', 'description' => 'Flights, hotels, vacation expenses'],
            ['name' => 'Personal Care', 'icon' => 'user', 'color' => '#F97316', 'description' => 'Haircuts, cosmetics, fitness'],
            ['name' => 'Other', 'icon' => 'more-horizontal', 'color' => '#6B7280', 'description' => 'Miscellaneous expenses'],
        ];

        // Get all users or create default categories for the first user
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->warn('No users found. Categories will not be created.');
            return;
        }

        foreach ($users as $user) {
            foreach ($categories as $category) {
                Category::firstOrCreate(
                    ['name' => $category['name'], 'user_id' => $user->id],
                    [...$category, 'user_id' => $user->id]
                );
            }
        }
    }
}
