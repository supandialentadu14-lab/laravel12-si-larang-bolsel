<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bts_tower_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bts_tower_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->nullOnDelete();
            $table->enum('type', ['catatan', 'perawatan', 'kerusakan', 'inspeksi'])->default('catatan');
            $table->string('judul');
            $table->text('isi');
            $table->date('tanggal')->nullable();
            $table->string('biaya', 20)->nullable();
            $table->string('teknisi')->nullable();
            $table->timestamps();

            $table->index(['bts_tower_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bts_tower_notes');
    }
};
