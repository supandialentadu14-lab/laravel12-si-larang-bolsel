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
        Schema::table('opd_settings', function (Blueprint $table) {
            $table->date('tutup_buku_date')->nullable()->after('singkatan_opd');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opd_settings', function (Blueprint $table) {
            $table->dropColumn('tutup_buku_date');
        });
    }
};
