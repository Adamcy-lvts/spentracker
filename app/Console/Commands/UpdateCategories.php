<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'categories:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update categories to use the new global category system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating categories to global system...');

        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clear existing categories
        Category::truncate();
        $this->info('Cleared existing categories.');
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Define the new global categories
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

        // Create the new categories
        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->info('Created ' . count($categories) . ' global categories.');
        $this->info('Categories updated successfully! 🎉');

        return Command::SUCCESS;
    }
}
