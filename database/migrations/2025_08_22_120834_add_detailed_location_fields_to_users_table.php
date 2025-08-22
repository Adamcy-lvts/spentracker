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
        Schema::table('users', function (Blueprint $table) {
            // Detailed location fields for precise tracking
            $table->string('last_login_street_address')->nullable();
            $table->string('last_login_neighborhood')->nullable();
            $table->string('last_login_district')->nullable();
            $table->string('last_login_state')->nullable();
            $table->string('last_login_postal_code')->nullable();
            $table->text('last_login_full_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'last_login_street_address',
                'last_login_neighborhood',
                'last_login_district',
                'last_login_state',
                'last_login_postal_code',
                'last_login_full_address'
            ]);
        });
    }
};