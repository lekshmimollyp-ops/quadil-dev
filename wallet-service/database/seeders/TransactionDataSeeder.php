<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionDataSeeder extends Seeder
{
    /**
     * Seed transaction history for ledger testing
     * 
     * Note: This seeder should run AFTER WalletDataSeeder
     */
    public function run(): void
    {
        // Get first 3 wallets (the ones with known tenant_ids)
        $wallets = DB::table('wallets')
            ->orderBy('created_at')
            ->limit(3)
            ->get();

        if ($wallets->count() < 3) {
            $this->command->error('❌ Not enough wallets found. Run WalletDataSeeder first.');
            return;
        }

        $transactions = [];
        
        // Wallet 1 (High balance: ₹50,000) - Active trading history
        $wallet1 = $wallets[0];
        $transactions[] = [
            'wallet_id' => $wallet1->id,
            'amount' => 100000.00,
            'type' => 'credit',
            'reference_id' => 'TOP-' . now()->subMonths(3)->format('Ymd') . '-001',
            'description' => 'Initial wallet top-up',
            'created_at' => now()->subMonths(3),
            'updated_at' => now()->subMonths(3),
        ];
        $transactions[] = [
            'wallet_id' => $wallet1->id,
            'amount' => 15000.00,
            'type' => 'debit',
            'reference_id' => 'ORD-' . now()->subMonths(2)->format('Ymd') . '-101',
            'description' => 'Order payment - Bulk delivery',
            'created_at' => now()->subMonths(2),
            'updated_at' => now()->subMonths(2),
        ];
        $transactions[] = [
            'wallet_id' => $wallet1->id,
            'amount' => 25000.00,
            'type' => 'credit',
            'reference_id' => 'TOP-' . now()->subMonth()->format('Ymd') . '-002',
            'description' => 'Monthly top-up',
            'created_at' => now()->subMonth(),
            'updated_at' => now()->subMonth(),
        ];
        $transactions[] = [
            'wallet_id' => $wallet1->id,
            'amount' => 8500.00,
            'type' => 'debit',
            'reference_id' => 'ORD-' . now()->subWeeks(2)->format('Ymd') . '-102',
            'description' => 'Order payment - Express delivery',
            'created_at' => now()->subWeeks(2),
            'updated_at' => now()->subWeeks(2),
        ];
        $transactions[] = [
            'wallet_id' => $wallet1->id,
            'amount' => 12000.00,
            'type' => 'debit',
            'reference_id' => 'ORD-' . now()->subWeek()->format('Ymd') . '-103',
            'description' => 'Order payment - Multiple pickups',
            'created_at' => now()->subWeek(),
            'updated_at' => now()->subWeek(),
        ];

        // Wallet 2 (Medium balance: ₹15,000) - Regular activity
        $wallet2 = $wallets[1];
        $transactions[] = [
            'wallet_id' => $wallet2->id,
            'amount' => 50000.00,
            'type' => 'credit',
            'reference_id' => 'TOP-' . now()->subMonths(2)->format('Ymd') . '-003',
            'description' => 'Initial deposit',
            'created_at' => now()->subMonths(2),
            'updated_at' => now()->subMonths(2),
        ];
        $transactions[] = [
            'wallet_id' => $wallet2->id,
            'amount' => 18000.00,
            'type' => 'debit',
            'reference_id' => 'ORD-' . now()->subMonth()->format('Ymd') . '-201',
            'description' => 'Order payment - Food delivery batch',
            'created_at' => now()->subMonth(),
            'updated_at' => now()->subMonth(),
        ];
        $transactions[] = [
            'wallet_id' => $wallet2->id,
            'amount' => 10000.00,
            'type' => 'credit',
            'reference_id' => 'TOP-' . now()->subWeeks(3)->format('Ymd') . '-004',
            'description' => 'Account top-up',
            'created_at' => now()->subWeeks(3),
            'updated_at' => now()->subWeeks(3),
        ];
        $transactions[] = [
            'wallet_id' => $wallet2->id,
            'amount' => 7500.00,
            'type' => 'debit',
            'reference_id' => 'ORD-' . now()->subWeek()->format('Ymd') . '-202',
            'description' => 'Order payment - Weekly deliveries',
            'created_at' => now()->subWeek(),
            'updated_at' => now()->subWeek(),
        ];

        // Wallet 3 (Low balance: ₹2,500) - Declining balance
        $wallet3 = $wallets[2];
        $transactions[] = [
            'wallet_id' => $wallet3->id,
            'amount' => 20000.00,
            'type' => 'credit',
            'reference_id' => 'TOP-' . now()->subMonth()->format('Ymd') . '-005',
            'description' => 'Initial funding',
            'created_at' => now()->subMonth(),
            'updated_at' => now()->subMonth(),
        ];
        $transactions[] = [
            'wallet_id' => $wallet3->id,
            'amount' => 8000.00,
            'type' => 'debit',
            'reference_id' => 'ORD-' . now()->subWeeks(3)->format('Ymd') . '-301',
            'description' => 'Order payment - Grocery delivery',
            'created_at' => now()->subWeeks(3),
            'updated_at' => now()->subWeeks(3),
        ];
        $transactions[] = [
            'wallet_id' => $wallet3->id,
            'amount' => 5500.00,
            'type' => 'debit',
            'reference_id' => 'ORD-' . now()->subWeeks(2)->format('Ymd') . '-302',
            'description' => 'Order payment - Local deliveries',
            'created_at' => now()->subWeeks(2),
            'updated_at' => now()->subWeeks(2),
        ];
        $transactions[] = [
            'wallet_id' => $wallet3->id,
            'amount' => 4000.00,
            'type' => 'debit',
            'reference_id' => 'ORD-' . now()->subWeek()->format('Ymd') . '-303',
            'description' => 'Order payment - Express service',
            'created_at' => now()->subWeek(),
            'updated_at' => now()->subWeek(),
        ];

        // Insert all transactions
        DB::table('transactions')->insert($transactions);

        $this->command->info('✅ Created ' . count($transactions) . ' transaction records');
        $this->command->info('   - Wallet 1: 5 transactions (3 credits, 2 debits)');
        $this->command->info('   - Wallet 2: 4 transactions (2 credits, 2 debits)');
        $this->command->info('   - Wallet 3: 4 transactions (1 credit, 3 debits)');
        $this->command->info('   Total: 13 transactions for ledger testing');
    }
}
