<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Simulate what the store() method does step by step
echo "=== Simulating User Store ===\n";

try {
    // Step 1: Validate + create
    $user = \App\Models\User::create([
        'name'     => 'Staff Test ' . date('His'),
        'email'    => 'staff_test_' . time() . '@test.com',
        'password' => \Illuminate\Support\Facades\Hash::make('Password123'),
        'role'     => 'staff',
    ]);
    echo "✅ User created: ID={$user->id}, Name={$user->name}\n";

    // Step 2: Check if still exists
    $check = \App\Models\User::find($user->id);
    echo "✅ User found after creation: " . ($check ? "YES" : "NO") . "\n";

    // Step 3: Check all users in DB now
    $allUsers = \App\Models\User::all();
    echo "Total users in DB: " . $allUsers->count() . "\n";
    foreach ($allUsers as $u) {
        echo "  - [{$u->id}] {$u->name}\n";
    }

    // Cleanup
    $user->delete();
    echo "\n🗑 Test user deleted.\n";

} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo $e->getTraceAsString() . "\n";
}
