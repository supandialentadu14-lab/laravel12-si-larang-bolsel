<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bts_tower_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bts_tower_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('path');
            $table->string('caption')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
            $table->index(['bts_tower_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bts_tower_photos');
    }
};
