<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Check notifications table structure
if (\Illuminate\Support\Facades\Schema::hasTable('notifications')) {
    echo "notifications: EXISTS\n";
    $cols = \Illuminate\Support\Facades\Schema::getColumnListing('notifications');
    echo "Columns: " . implode(', ', $cols) . "\n";
} else {
    echo "notifications: MISSING\n";
}

// Check activity_logs table structure
if (\Illuminate\Support\Facades\Schema::hasTable('activity_logs')) {
    echo "\nactivity_logs: EXISTS\n";
    $cols = \Illuminate\Support\Facades\Schema::getColumnListing('activity_logs');
    echo "Columns: " . implode(', ', $cols) . "\n";
} else {
    echo "activity_logs: MISSING\n";
}

// Test Auth::user()->unreadNotifications query directly
try {
    $user = \App\Models\User::first();
    $count = $user->unreadNotifications()->count();
    echo "\nunreadNotifications count: $count (OK)\n";
} catch (\Exception $e) {
    echo "\nunreadNotifications ERROR: " . $e->getMessage() . "\n";
}
