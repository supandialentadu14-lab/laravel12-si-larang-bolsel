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
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('stock', 15, 2)->change();
            $table->decimal('min_stock', 15, 2)->change();
        });

        Schema::table('nota_pesanans', function (Blueprint $table) {
            $table->decimal('total', 20, 2)->change();
        });

        Schema::table('nota_items', function (Blueprint $table) {
            $table->decimal('qty', 15, 2)->change();
            $table->decimal('price', 15, 2)->change();
            $table->decimal('total', 20, 2)->change();
        });

        Schema::table('bap_pemeriksaans', function (Blueprint $table) {
            $table->decimal('total', 20, 2)->change();
        });

        Schema::table('bap_items', function (Blueprint $table) {
            $table->decimal('kuantitas', 15, 2)->change();
            $table->decimal('harga', 15, 2)->change();
            $table->decimal('jumlah', 15, 2)->change();
        });

        Schema::table('bap_penerimaans', function (Blueprint $table) {
            $table->decimal('total', 20, 2)->change();
        });

        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->decimal('quantity', 15, 2)->change();
        });
    }

    public function down(): void
    {
        // No down migration specified to avoid reverting to broken integer limits
    }
};
