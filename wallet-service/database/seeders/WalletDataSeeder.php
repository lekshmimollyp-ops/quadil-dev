<?php

namespace Database\Seeders;

use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WalletDataSeeder extends Seeder
{
    /**
     * Seed wallet test data for financial testing
     */
    public function run(): void
    {
        // Tenant IDs from tenant-service (matching actual DB records)
        $tenantIds = [
            '2a7e25e8-17e6-49df-a8c9-7557d1887e22', // Tenant 1
            '2ebaba1f-efba-497d-91d4-c01616b03074', // Tenant 2
            '2011f523-cd43-4dff-b465-fd76925473de', // Tenant 3
        ];

        $wallets = [
            // High balance wallet - healthy merchant
            [
                'id' => Str::uuid()->toString(),
                'tenant_id' => $tenantIds[0],
                'advance_balance' => 50000.00,
                'credit_limit' => 25000.00,
                'is_active' => true,
                'created_at' => now()->subMonths(3),
                'updated_at' => now(),
            ],
            
            // Medium balance wallet - normal operations
            [
                'id' => Str::uuid()->toString(),
                'tenant_id' => $tenantIds[1],
                'advance_balance' => 15000.00,
                'credit_limit' => 10000.00,
                'is_active' => true,
                'created_at' => now()->subMonths(2),
                'updated_at' => now(),
            ],
            
            // Low balance wallet - for low balance alert testing
            [
                'id' => Str::uuid()->toString(),
                'tenant_id' => $tenantIds[2],
                'advance_balance' => 2500.00,
                'credit_limit' => 5000.00,
                'is_active' => true,
                'created_at' => now()->subMonth(),
                'updated_at' => now(),
            ],
            
            // Very low balance - critical alert
            [
                'id' => Str::uuid()->toString(),
                'tenant_id' => Str::uuid()->toString(), // New tenant
                'advance_balance' => 500.00,
                'credit_limit' => 5000.00,
                'is_active' => true,
                'created_at' => now()->subWeeks(2),
                'updated_at' => now(),
            ],
            
            // Zero balance wallet - needs top-up
            [
                'id' => Str::uuid()->toString(),
                'tenant_id' => Str::uuid()->toString(), // New tenant
                'advance_balance' => 0.00,
                'credit_limit' => 10000.00,
                'is_active' => true,
                'created_at' => now()->subWeek(),
                'updated_at' => now(),
            ],
            
            // Inactive wallet - suspended merchant
            [
                'id' => Str::uuid()->toString(),
                'tenant_id' => Str::uuid()->toString(), // New tenant
                'advance_balance' => 1000.00,
                'credit_limit' => 0.00,
                'is_active' => false,
                'created_at' => now()->subMonths(6),
                'updated_at' => now()->subMonth(),
            ],
        ];

        foreach ($wallets as $wallet) {
            Wallet::create($wallet);
        }

        $this->command->info('✅ Created ' . count($wallets) . ' wallet records');
        $this->command->info('   - High balance: ₹50,000 (Buying Power: ₹75,000)');
        $this->command->info('   - Medium balance: ₹15,000 (Buying Power: ₹25,000)');
        $this->command->info('   - Low balance: ₹2,500 (Buying Power: ₹7,500)');
        $this->command->info('   - Very low: ₹500 (Buying Power: ₹5,500)');
        $this->command->info('   - Zero balance: ₹0 (Buying Power: ₹10,000)');
        $this->command->info('   - Inactive: ₹1,000 (Suspended)');
    }
}
