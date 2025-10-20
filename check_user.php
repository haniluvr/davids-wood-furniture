<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\User::find(151);
echo 'User 151 exists: '.($user ? 'YES' : 'NO').PHP_EOL;

if ($user) {
    echo 'User details: '.$user->name.' ('.$user->email.')'.PHP_EOL;
} else {
    echo 'Total users: '.App\Models\User::count().PHP_EOL;
    echo 'Max user ID: '.App\Models\User::max('id').PHP_EOL;
    echo 'Recent users:'.PHP_EOL;
    $recentUsers = App\Models\User::orderBy('id', 'desc')->limit(5)->get();
    foreach ($recentUsers as $u) {
        echo "  ID: {$u->id}, Name: {$u->name}, Email: {$u->email}".PHP_EOL;
    }
}
