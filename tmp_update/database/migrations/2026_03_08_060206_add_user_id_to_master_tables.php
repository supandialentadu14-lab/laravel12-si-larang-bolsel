<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add user_id to categories
        if (!Schema::hasColumn('categories', 'user_id')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
            });
        }
        
        // Add user_id to suppliers
        if (!Schema::hasColumn('suppliers', 'user_id')) {
            Schema::table('suppliers', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
            });
        }
        
        // Add user_id to products
        if (!Schema::hasColumn('products', 'user_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
            });
        }

        // Get the oldest admin user to assign existing records
        $defaultUserId = User::where('role', 'admin')->orderBy('id', 'asc')->value('id');

        // If a default user exists, assign existing records to this user
        if ($defaultUserId) {
            DB::table('categories')->whereNull('user_id')->update(['user_id' => $defaultUserId]);
            DB::table('suppliers')->whereNull('user_id')->update(['user_id' => $defaultUserId]);
            DB::table('products')->whereNull('user_id')->update(['user_id' => $defaultUserId]);
        }
        
        // After assigning existing records, make the columns non-nullable
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
        });
        
        Schema::table('suppliers', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
        });
        
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
        
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
        
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
