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
        // Ubah kolom type agar menerima nilai 'saldo' selain 'in' dan 'out'
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->string('type', 10)->change();
        });
    }

    public function down(): void
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->enum('type', ['in', 'out'])->change();
        });
    }
};
