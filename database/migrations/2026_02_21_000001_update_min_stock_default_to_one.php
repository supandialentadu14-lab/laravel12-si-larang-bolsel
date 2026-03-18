<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('min_stock')->default(1)->change();
        });
        DB::table('products')->where('min_stock', 10)->update(['min_stock' => 1]);
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('min_stock')->default(10)->change();
        });
    }
};
