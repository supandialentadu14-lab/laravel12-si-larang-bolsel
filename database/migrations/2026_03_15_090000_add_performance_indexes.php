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
        // Index for Stock Transactions (Performance for History & Reports)
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->index(['user_id', 'date']); 
            $table->index(['product_id', 'date', 'id']); // Optimization for running balance
            $table->index('type');
        });

        // Index for Products (Performance for Multi-tenant filtering)
        Schema::table('products', function (Blueprint $table) {
            $table->index(['user_id', 'category_id']);
        });

        // Index for Activity Logs
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->index(['user_id', 'created_at']);
            $table->index('action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'date']);
            $table->dropIndex(['type']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'category_id']);
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'created_at']);
            $table->dropIndex(['action']);
        });
    }
};
