<?php

namespace App\Http\Controllers;

use App\Models\AnalyticsSummary;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    /**
     * Get analytics for a specific merchant
     */
    public function getMerchantStats($tenant_id)
    {
        $stats = AnalyticsSummary::where('tenant_id', $tenant_id)->first();

        if (!$stats) {
            return response()->json([
                'tenant_id' => $tenant_id,
                'total_orders' => 0,
                'total_revenue' => 0.00,
                'completed_orders' => 0,
                'cancelled_orders' => 0,
                'message' => 'Initial stats (empty)'
            ]);
        }

        return response()->json($stats);
    }

    /**
     * Get platform-wide aggregated stats
     */
    public function getPlatformStats()
    {
        return response()->json([
            'total_tenants' => AnalyticsSummary::count(),
            'platform_total_orders' => AnalyticsSummary::sum('total_orders'),
            'platform_total_revenue' => AnalyticsSummary::sum('total_revenue'),
            'platform_completed_orders' => AnalyticsSummary::sum('completed_orders'),
        ]);
    }

    /**
     * Simulate a data refresh/ingestion
     */
    public function refresh(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|uuid',
            'orders' => 'required|integer',
            'revenue' => 'required|numeric',
        ]);

        $stats = AnalyticsSummary::updateOrCreate(
            ['tenant_id' => $request->tenant_id],
            [
                'total_orders' => $request->orders,
                'total_revenue' => $request->revenue,
                'completed_orders' => $request->orders - rand(0, 2), // Simulated completion rate
                'last_order_at' => now(),
            ]
        );

        return response()->json([
            'message' => 'Analytics refreshed',
            'data' => $stats
        ]);
    }
    public function getTodayRevenue()
    {
        $revenue = AnalyticsSummary::sum('total_revenue');
        return response()->json([
            'amount' => (float)$revenue,
            'change' => '+8.2%',
            'trend' => 'up'
        ]);
    }
}
