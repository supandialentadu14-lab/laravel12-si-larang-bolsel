<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bts_towers', function (Blueprint $table) {
            // tipe_tower jadi teks bebas (bisa ketik manual), boleh kosong
            $table->string('tipe_tower', 100)->nullable()->default(null)->change();
            // kondisi & status_operasional tetap pakai daftar pilihan di form,
            // tapi di database jadi teks biasa supaya boleh kosong (nullable)
            $table->string('kondisi', 50)->nullable()->default(null)->change();
            $table->string('status_operasional', 50)->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        Schema::table('bts_towers', function (Blueprint $table) {
            $table->enum('tipe_tower', ['Self Supporting Tower (SST)', 'Guyed Mast', 'Monopole', 'Microcell/Pole', 'Rooftop'])->default('Monopole')->change();
            $table->enum('kondisi', ['Baik', 'Rusak Ringan', 'Rusak Berat', 'Perlu Perbaikan'])->default('Baik')->change();
            $table->enum('status_operasional', ['Aktif', 'Tidak Aktif', 'Maintenance'])->default('Aktif')->change();
        });
    }
};
