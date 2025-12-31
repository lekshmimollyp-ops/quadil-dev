<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$ids = App\Models\Tenant::pluck('id')->take(3)->toArray();
foreach ($ids as $id) {
    echo $id . "\n";
}
