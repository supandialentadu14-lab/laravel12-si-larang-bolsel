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
        Schema::table('categories', function (Blueprint $table) {
            // Drop existing global unique constraint
            $table->dropUnique(['slug']);
            // Add new unique constraint scoped to user_id
            $table->unique(['user_id', 'slug']);
        });

        Schema::table('products', function (Blueprint $table) {
            // Drop existing global unique constraints
            $table->dropUnique(['slug']);
            $table->dropUnique(['sku']);
            // Add new unique constraints scoped to user_id
            $table->unique(['user_id', 'slug']);
            $table->unique(['user_id', 'sku']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'slug']);
            $table->dropUnique(['user_id', 'sku']);
            $table->unique('slug');
            $table->unique('sku');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'slug']);
            $table->unique('slug');
        });
    }
};
