<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Inertia\Response;

class AnalyticsController extends Controller
{
    protected $baseUrl;

    public function __construct() {
        $this->baseUrl = config('services.gateway.url') . '/analytics/api/v1';
    }

    /**
     * Display the analytics dashboard.
     */
    public function index(): Response
    {
        $token = session('remote_token');
        
        try {
            // Fetch Platform Stats
            $platformRes = Http::withToken($token)->timeout(5)
                ->get("{$this->baseUrl}/platform");
            $platformStats = $platformRes->successful() ? $platformRes->json() : [];

            // Fetch Today's Revenue
            $todayRevenueRes = Http::withToken($token)->timeout(5)
                ->get("{$this->baseUrl}/revenue/today");
            $todayRevenue = $todayRevenueRes->successful() ? $todayRevenueRes->json() : ['amount' => 0, 'change' => '0%', 'trend' => 'neutral'];

        } catch (\Exception $e) {
            \Log::error("Failed to fetch analytics: " . $e->getMessage());
            $platformStats = [];
            $todayRevenue = ['amount' => 0, 'change' => '0%', 'trend' => 'neutral'];
        }

        return Inertia::render('Analytics/Index', [
            'platformStats' => $platformStats,
            'todayRevenue' => $todayRevenue,
        ]);
    }

    /**
     * Get analytics for a specific merchant via AJAX.
     */
    public function getMerchantStats(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|uuid',
        ]);

        $token = session('remote_token');

        try {
            $response = Http::withToken($token)->timeout(5)
                ->get("{$this->baseUrl}/merchant/{$request->tenant_id}");

            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch merchant stats'], 500);
        }
    }
}
