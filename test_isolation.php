<?php

// Simulate login as Admin
$admin = \App\Models\User::where('role', 'admin')->first();
\Illuminate\Support\Facades\Auth::login($admin);

echo "\n--- Admin Logged In ({$admin->name}) ---\n";
// Create a category
$catAdmin = \App\Models\Category::create([
    'name' => 'Kategori Admin ' . time(),
    'slug' => 'ADM-' . time(),
    'description' => 'Created by Admin'
]);
echo "- Created Category ID: {$catAdmin->id}, User ID: {$catAdmin->user_id}\n";
echo "- Total categories visible: " . \App\Models\Category::count() . "\n";


// Simulate login as Staff
$staff = \App\Models\User::where('role', 'staff')->first();
if (!$staff) {
    echo "No staff user found to test isolation.\n";
    exit;
}

\Illuminate\Support\Facades\Auth::login($staff);

echo "\n--- Staff Logged In ({$staff->name}) ---\n";
// Create a category
$catStaff = \App\Models\Category::create([
    'name' => 'Kategori Staff ' . time(),
    'slug' => 'STF-' . time(),
    'description' => 'Created by Staff'
]);
echo "- Created Category ID: {$catStaff->id}, User ID: {$catStaff->user_id}\n";
echo "- Total categories visible: " . \App\Models\Category::count() . "\n";

// Log back in as admin
\Illuminate\Support\Facades\Auth::login($admin);
echo "\n--- Admin Logged In Again ({$admin->name}) ---\n";
echo "- Total categories visible: " . \App\Models\Category::count() . "\n";
