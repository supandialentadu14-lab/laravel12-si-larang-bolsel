<?php
try {
    $u = new App\Models\User();
    $u->name = 'Tester';
    $u->email = 'tester@example.com';
    $u->password = Illuminate\Support\Facades\Hash::make('password');
    $u->role = 'admin';
    $u->save();
    echo "SUCCESS: " . $u->id . PHP_EOL;
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
}
