<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if user_id column already exists
        if (!Schema::hasColumn('categories', 'user_id')) {
            Schema::table('categories', function (Blueprint $table) {
                // Add user_id column as nullable first
                $table->unsignedBigInteger('user_id')->nullable();
            });
        }

        // Assign existing categories to the first user (or create a default user)
        $firstUser = \App\Models\User::first();
        if ($firstUser) {
            \Illuminate\Support\Facades\DB::table('categories')->whereNull('user_id')->update(['user_id' => $firstUser->id]);
        }

        Schema::table('categories', function (Blueprint $table) {
            // Now make it non-nullable and add foreign key constraint
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            
            // Check if foreign key doesn't already exist
            if (!Schema::hasColumn('categories', 'user_id') || 
                !collect(\Illuminate\Support\Facades\DB::select("SHOW CREATE TABLE categories"))->first()->{'Create Table'} ||
                !str_contains(collect(\Illuminate\Support\Facades\DB::select("SHOW CREATE TABLE categories"))->first()->{'Create Table'}, 'categories_user_id_foreign')) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
            
            // Handle unique constraints
            try {
                $table->dropUnique('categories_name_unique');
            } catch (\Exception $e) {
                // Constraint might not exist
            }
            
            try {
                $table->unique(['name', 'user_id']);
            } catch (\Exception $e) {
                // Constraint might already exist
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['name', 'user_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->unique('name');
        });
    }
};