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
        Schema::table('bts_towers', function (Blueprint $table) {
            $table->string('nama_perusahaan')->nullable()->after('provider');
        });
    }

    public function down(): void
    {
        Schema::table('bts_towers', function (Blueprint $table) {
            $table->dropColumn('nama_perusahaan');
        });
    }
};
