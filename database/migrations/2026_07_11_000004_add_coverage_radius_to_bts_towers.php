<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bts_towers', function (Blueprint $table) {
            $table->decimal('coverage_radius', 6, 2)->nullable()->after('keterangan');
        });
    }

    public function down(): void
    {
        Schema::table('bts_towers', function (Blueprint $table) {
            $table->dropColumn('coverage_radius');
        });
    }
};
