<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bts_towers', function (Blueprint $table) {
            $table->id();
            $table->string('kode_bts')->unique();
            $table->string('nama_bts');
            $table->enum('provider', ['Telkomsel', 'Indosat', 'XL Axiata', 'Tri (3)', 'Smartfren', 'Lainnya'])->default('Lainnya');
            $table->enum('kecamatan', [
                'Bolaang Uki',
                'Helumo',
                'Pinolosian',
                'Pinolosian Tengah',
                'Pinolosian Timur',
                'Posigadan',
                'Tomini',
            ]);
            $table->string('desa')->nullable();
            $table->text('alamat')->nullable();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->decimal('tinggi_tower', 6, 2)->nullable()->comment('meter');
            $table->enum('tipe_tower', ['Self Supporting Tower (SST)', 'Guyed Mast', 'Monopole', 'Microcell/Pole', 'Rooftop'])->default('Monopole');
            $table->enum('kondisi', ['Baik', 'Rusak Ringan', 'Rusak Berat', 'Perlu Perbaikan'])->default('Baik');
            $table->enum('status_operasional', ['Aktif', 'Tidak Aktif', 'Maintenance'])->default('Aktif');
            $table->year('tahun_dibangun')->nullable();
            $table->string('foto')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['kecamatan', 'provider', 'status_operasional']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bts_towers');
    }
};
