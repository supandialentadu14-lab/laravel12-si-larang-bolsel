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
        Schema::table('bap_pemeriksaans', function (Blueprint $table) {
            $table->bigInteger('total')->change();
        });
        
        Schema::table('bap_items', function (Blueprint $table) {
            $table->bigInteger('harga')->change();
            $table->bigInteger('jumlah')->change();
        });
    }

    public function down(): void
    {
        Schema::table('bap_pemeriksaans', function (Blueprint $table) {
            $table->integer('total')->change();
        });

        Schema::table('bap_items', function (Blueprint $table) {
            $table->integer('harga')->change();
            $table->integer('jumlah')->change();
        });
    }
};
