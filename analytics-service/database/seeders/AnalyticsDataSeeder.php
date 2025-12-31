<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\AnalyticsSummary;
use Illuminate\Support\Str;

class AnalyticsDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Real Tenants from Tenant Service
        $realTenants = [
            '2a7e25e8-17e6-49df-a8c9-7557d1887e22', // Dhanya Supermarket
            '2ebaba1f-efba-497d-91d4-c01616b03074', // Downtown Branch
            '2011f523-cd43-4dff-b465-fd76925473de', // Other Merchant
        ];

        foreach ($realTenants as $tenantId) {
            AnalyticsSummary::updateOrCreate(
                ['tenant_id' => $tenantId],
                [
                    'total_orders' => rand(50, 200),
                    'total_revenue' => rand(5000, 25000) + (rand(0, 99) / 100),
                    'completed_orders' => rand(40, 180),
                    'cancelled_orders' => rand(0, 10),
                    'last_order_at' => now()->subMinutes(rand(1, 1440)),
                ]
            );
        }

        // 2. Add some synthetic data to boost platform stats
        for ($i = 0; $i < 5; $i++) {
            AnalyticsSummary::create([
                'tenant_id' => Str::uuid(),
                'total_orders' => rand(10, 50),
                'total_revenue' => rand(1000, 5000) + (rand(0, 99) / 100),
                'completed_orders' => rand(5, 40),
                'cancelled_orders' => rand(0, 5),
                'last_order_at' => now()->subDays(rand(1, 30)),
            ]);
        }
    }
}
