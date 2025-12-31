<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Update agents to online status
$updated = DB::table('agents')
    ->whereIn('user_id', [1003, 1004, 1005])
    ->update(['current_status' => 'online']);

echo "✅ Updated {$updated} agents to online status\n";

// Show online agents
$onlineAgents = DB::table('agents')
    ->where('current_status', 'online')
    ->get(['id', 'user_id', 'current_status']);

echo "\n📋 Online Agents:\n";
foreach ($onlineAgents as $agent) {
    echo "  - Agent ID: {$agent->id}, User ID: {$agent->user_id} (Status: {$agent->current_status})\n";
}

echo "\n✅ Done! Agents are now available for dispatch.\n";
