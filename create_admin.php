<?php

use App\Models\User;

$user = new User;
$user->name = 'Emon Alentadu';
$user->email = 'supandialentadu14@gmail.com';
$user->password = bcrypt('Emon@1996');
$user->role = 'admin';
$user->save();

echo "Admin created successfully!\n";
