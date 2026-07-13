<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('stock', 15, 2)->default(0.00)->change();
            $table->decimal('min_stock', 15, 2)->default(1.00)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('stock', 15, 2)->change();
            $table->decimal('min_stock', 15, 2)->change();
        });
    }
};
