<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$deleted = DB::table('users')
    ->orderBy('created_at', 'desc')
    ->limit(3)
    ->pluck('id')
    ->toArray();

if (empty($deleted)) {
    echo "No users to delete\n";
    exit(0);
}

DB::table('users')->whereIn('id', $deleted)->delete();

echo "Deleted users: " . implode(', ', $deleted) . "\n";
