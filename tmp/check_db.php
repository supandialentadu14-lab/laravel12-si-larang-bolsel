<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Check raw DB
$users = \Illuminate\Support\Facades\DB::table('users')->get();
echo "Raw DB total users: " . $users->count() . "\n";
foreach ($users as $u) {
    echo "- ID:{$u->id} | {$u->name} | {$u->email} | Role:{$u->role}\n";
}

// Check auto-increment
$autoInc = \Illuminate\Support\Facades\DB::select("SHOW TABLE STATUS LIKE 'users'");
echo "\nAuto increment: " . $autoInc[0]->Auto_increment . "\n";
