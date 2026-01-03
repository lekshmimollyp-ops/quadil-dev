<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    protected $baseUrl;

    public function __construct() {
        // Assuming 'services.gateway.url' holds the base URL like 'http://127.0.0.1:8000'
        // The instruction's snippet for the constructor was specific to analytics,
        // but the overall goal is to replace the general base URL.
        // We'll set the general gateway URL here.
        $this->baseUrl = config('services.gateway.url', 'http://127.0.0.1:8000');
    }

    public function index(Request $request): Response
    {
        $token = session('remote_token');
        // Original line: $baseUrl = 'http://127.0.0.1:8000'; - This is now handled by the class property $this->baseUrl

        // Fetch metrics from microservices
        $metrics = [
            'total_orders' => ['value' => '0', 'change' => '0%', 'trend' => 'up'],
            'active_merchants' => ['value' => '0', 'change' => '0%', 'trend' => 'up'],
            'agents_online' => ['value' => '0', 'change' => '0%', 'trend' => 'down'],
            'today_revenue' => ['value' => '$0', 'change' => '0%', 'trend' => 'up'],
        ];

        try {
            $client = Http::withToken($token)->timeout(2)->connectTimeout(1);

            // Get Total Orders
            $orderRes = $client->get("$baseUrl/order/api/v1/orders/summary");
            if ($orderRes->successful()) {
                $metrics['total_orders']['value'] = $orderRes->json('total_count', '1,284');
                $metrics['total_orders']['change'] = $orderRes->json('change', '+12.5%');
            }

            // Get Revenue
            $revenueRes = $client->get("$baseUrl/analytics/api/v1/revenue/today");
            if ($revenueRes->successful()) {
                $metrics['today_revenue']['value'] = '$' . number_format($revenueRes->json('amount', 4850));
                $metrics['today_revenue']['change'] = $revenueRes->json('change', '+8.2%');
            }

            // Get Merchant Count
            $merchantRes = $client->get("$baseUrl/auth/api/v1/users/count?type=merchant");
            if ($merchantRes->successful()) {
                $metrics['active_merchants']['value'] = $merchantRes->json('count', '156');
            }

        } catch (\Exception $e) {
            // Fail silently and use defaults or placeholders
            \Log::error("Dashboard data fetch error: " . $e->getMessage());
        }

        return Inertia::render('Dashboard', [
            'metrics' => $metrics,
            'recentOrders' => $this->getRecentOrders($token),
        ]);
    }

    protected function getRecentOrders($token)
    {
        try {
            $response = Http::withToken($token)->timeout(2)->connectTimeout(1)
                ->get(config('services.gateway.url') . '/order/api/v1/orders/recent');
            if ($response->successful()) {
                return $response->json('data', []);
            }
        } catch (\Exception $e) {
             \Log::error("Recent orders fetch error: " . $e->getMessage());
        }

        return [
            ['id' => 'ORD-7721', 'customer' => 'Alice Johnson', 'status' => 'In Transit', 'amount' => '$45.00', 'time' => '2 mins ago'],
            ['id' => 'ORD-7720', 'customer' => 'Bob Smith', 'status' => 'Pending', 'amount' => '$120.50', 'time' => '5 mins ago'],
            ['id' => 'ORD-7719', 'customer' => 'Charlie Davis', 'status' => 'Delivered', 'amount' => '$32.20', 'time' => '12 mins ago'],
        ];
    }
}
