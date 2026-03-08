<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$users = App\Models\User::all();
echo "Total users: " . $users->count() . "\n";
foreach ($users as $u) {
    echo "- [{$u->id}] {$u->name} ({$u->email}) - {$u->role}\n";
}
