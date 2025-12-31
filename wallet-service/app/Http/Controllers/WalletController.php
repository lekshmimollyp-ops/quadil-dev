<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    /**
     * Get wallet balance for a tenant
     */
    public function getBalance($tenant_id)
    {
        $wallet = Wallet::firstOrCreate(
            ['tenant_id' => $tenant_id],
            ['advance_balance' => 0, 'credit_limit' => 0]
        );

        return response()->json($wallet);
    }

    /**
     * Top up the wallet
     */
    public function topup(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|uuid',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string'
        ]);

        return DB::transaction(function () use ($request) {
            $wallet = Wallet::firstOrCreate(
                ['tenant_id' => $request->tenant_id],
                ['advance_balance' => 0, 'credit_limit' => 0]
            );

            $wallet->increment('advance_balance', $request->amount);

            $wallet->transactions()->create([
                'amount' => $request->amount,
                'type' => 'credit',
                'description' => $request->description ?? 'Wallet Top-up',
            ]);

            return response()->json([
                'message' => 'Top-up successful',
                'wallet' => $wallet
            ]);
        });
    }

    /**
     * Deduct from the wallet
     */
    public function deduct(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|uuid',
            'amount' => 'required|numeric|min:0.01',
            'reference_id' => 'nullable|string',
            'description' => 'nullable|string'
        ]);

        return DB::transaction(function () use ($request) {
            $wallet = Wallet::where('tenant_id', $request->tenant_id)->first();

            if (!$wallet) {
                return response()->json(['message' => 'Wallet not found'], 404);
            }

            // Simple check: Check if advance_balance is enough
            if ($wallet->advance_balance < $request->amount) {
                return response()->json(['message' => 'Insufficient balance'], 400);
            }

            $wallet->decrement('advance_balance', $request->amount);

            // AUTO-DISABLE LOGIC: If total buying power < 0, disable service
            $totalPower = $wallet->advance_balance + $wallet->credit_limit;
            if ($totalPower < 0) {
                // Trigger tenant disablement via tenant-service (Simulation)
                \Log::info("AUTO-DISABLE: Tenant {$wallet->tenant_id} has insufficient funds ($totalPower). Service suspended.");
                // In production, we would call: Http::patch("tenant-service/v1/tenants/{$id}", ['is_active' => false])
            }

            $wallet->transactions()->create([
                'amount' => $request->amount,
                'type' => 'debit',
                'reference_id' => $request->reference_id,
                'description' => $request->description ?? 'Service Deduction',
            ]);

            return response()->json([
                'message' => 'Deduction successful',
                'wallet' => $wallet,
                'suspended' => $totalPower < 0
            ]);
        });
    }

    /**
     * Admin Endpoint: Update merchant credit limit
     */
    public function updateCreditLimit(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|uuid',
            'credit_limit' => 'required|numeric|min:0',
        ]);

        $wallet = Wallet::where('tenant_id', $request->tenant_id)->first();
        if (!$wallet) {
            return response()->json(['message' => 'Wallet not found'], 404);
        }

        $wallet->update(['credit_limit' => $request->credit_limit]);

        return response()->json([
            'message' => 'Credit limit updated successfully',
            'wallet' => $wallet
        ]);
    }
    /**
     * Get transaction ledger for a tenant
     */
    public function getLedger($tenant_id)
    {
        $wallet = Wallet::where('tenant_id', $tenant_id)->first();

        if (!$wallet) {
            return response()->json([
                'summary' => [
                    'total_credited' => 0,
                    'total_debited' => 0
                ],
                'entries' => []
            ]);
        }

        $entries = $wallet->transactions()->latest()->get();
        
        $summary = [
            'total_credited' => $wallet->transactions()->where('type', 'credit')->sum('amount'),
            'total_debited' => $wallet->transactions()->where('type', 'debit')->sum('amount'),
        ];

        return response()->json([
            'summary' => $summary,
            'entries' => $entries
        ]);
    }

    /**
     * Get platform financial summary
     */
    public function getSummary()
    {
        $totalCredited = DB::table('transactions')->where('type', 'credit')->sum('amount');
        $totalDebited = DB::table('transactions')->where('type', 'debit')->sum('amount');
        $totalTransactions = DB::table('transactions')->count();

        return response()->json([
            'platform_revenue' => $totalCredited, // Total Inflow
            'platform_expense' => $totalDebited,  // Total Outflow
            'total_transactions' => $totalTransactions
        ]);
    }
}
