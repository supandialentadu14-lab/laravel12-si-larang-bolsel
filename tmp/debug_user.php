<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Check if tables exist
$tables = ['activity_logs', 'notifications', 'users'];
foreach ($tables as $table) {
    $exists = \Illuminate\Support\Facades\Schema::hasTable($table);
    echo "$table: " . ($exists ? "EXISTS" : "MISSING") . "\n";
}

// Try creating a test user
try {
    $user = \App\Models\User::create([
        'name' => 'Test User Sementara',
        'email' => 'test_'.time().'@example.com',
        'password' => \Illuminate\Support\Facades\Hash::make('Password123'),
        'role' => 'staff',
    ]);
    echo "\nUser created OK: ID=" . $user->id . "\n";
    $user->delete();
    echo "User deleted OK.\n";
} catch (\Exception $e) {
    echo "\nERROR creating user: " . $e->getMessage() . "\n";
    echo "In: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
