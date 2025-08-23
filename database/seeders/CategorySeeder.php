<?php

namespace Database\Seeders;

use App\Models\Category;
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
            ['name' => 'Groceries', 'icon' => 'shopping-cart', 'color' => '#10B981', 'description' => 'Food items, market shopping, supermarket'],
            ['name' => 'Food & Dining', 'icon' => 'utensils', 'color' => '#34D399', 'description' => 'Restaurants, snacks, fast food'],
            ['name' => 'Transportation', 'icon' => 'car', 'color' => '#3B82F6', 'description' => 'Fuel, public transport, ride hailing, car maintenance'],
            ['name' => 'Bills & Utilities', 'icon' => 'receipt', 'color' => '#EF4444', 'description' => 'Electricity, water, rent, internet, airtime, data'],
            ['name' => 'Shopping', 'icon' => 'shopping-bag', 'color' => '#8B5CF6', 'description' => 'Clothing, electronics, household items'],
            ['name' => 'Healthcare', 'icon' => 'heart', 'color' => '#EC4899', 'description' => 'Hospital, medicine, pharmacy, insurance'],
            ['name' => 'Education', 'icon' => 'graduation-cap', 'color' => '#06B6D4', 'description' => 'School fees, courses, books, training'],
            ['name' => 'Savings & Investments', 'icon' => 'piggy-bank', 'color' => '#FBBF24', 'description' => 'Savings, cooperative, stocks, crypto'],
            ['name' => 'Entertainment', 'icon' => 'film', 'color' => '#F59E0B', 'description' => 'Movies, concerts, Netflix, hobbies'],
            ['name' => 'Travel', 'icon' => 'plane', 'color' => '#84CC16', 'description' => 'Flights, hotels, trips, vacation'],
            ['name' => 'Personal Care', 'icon' => 'user', 'color' => '#F97316', 'description' => 'Barbing, salon, cosmetics, fitness'],
            ['name' => 'Gifts & Donations', 'icon' => 'gift', 'color' => '#6366F1', 'description' => 'Charity, family support, religious giving'],
            ['name' => 'Other', 'icon' => 'more-horizontal', 'color' => '#6B7280', 'description' => 'Miscellaneous uncategorized expenses'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
