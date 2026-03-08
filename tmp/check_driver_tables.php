<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$check = ['sessions', 'jobs', 'cache', 'cache_locks', 'failed_jobs'];
foreach ($check as $table) {
    $exists = \Illuminate\Support\Facades\Schema::hasTable($table);
    echo "$table: " . ($exists ? "✅ EXISTS" : "❌ MISSING") . "\n";
}
