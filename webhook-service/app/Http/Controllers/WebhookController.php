<?php

namespace App\Http\Controllers;

use App\Models\WebhookConfig;
use App\Models\WebhookLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WebhookController extends Controller
{
    /**
     * Register a new webhook
     */
    public function register(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|uuid',
            'url' => 'required|url',
            'events' => 'required|array',
            'events.*' => 'string',
        ]);

        $config = WebhookConfig::updateOrCreate(
            ['tenant_id' => $request->tenant_id, 'url' => $request->url],
            [
                'secret' => Str::random(32),
                'events' => $request->events,
                'is_active' => true,
            ]
        );

        return response()->json([
            'message' => 'Webhook registered successfully',
            'config' => $config
        ], 201);
    }

    /**
     * List all webhooks for a tenant
     */
    public function index(Request $request)
    {
        $tenant_id = $request->query('tenant_id');
        $configs = WebhookConfig::when($tenant_id, function($q) use ($tenant_id) {
            return $q->where('tenant_id', $tenant_id);
        })->get();

        return response()->json($configs);
    }

    /**
     * Simulate dispatching a webhook event
     */
    public function dispatchEvent(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|uuid',
            'event' => 'required|string',
            'payload' => 'required|array',
        ]);

        $configs = WebhookConfig::where('tenant_id', $request->tenant_id)
            ->where('is_active', true)
            ->whereJsonContains('events', $request->event)
            ->get();

        if ($configs->isEmpty()) {
            return response()->json(['message' => 'No active webhooks found for this event'], 404);
        }

        $logs = [];
        foreach ($configs as $config) {
            // In a real system, we would use Guzzle/Http client here
            // Simulating a dispatch to $config->url
            $responseCode = 200; 
            $status = 'success';

            $log = WebhookLog::create([
                'tenant_id' => $request->tenant_id,
                'event' => $request->event,
                'request_payload' => $request->payload,
                'response_code' => $responseCode,
                'status' => $status,
            ]);
            $logs[] = $log;
        }

        return response()->json([
            'message' => 'Event dispatched to ' . $configs->count() . ' endpoint(s)',
            'logs' => $logs
        ]);
    }
}
