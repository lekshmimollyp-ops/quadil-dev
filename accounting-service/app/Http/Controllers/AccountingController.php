<?php

namespace App\Http\Controllers;

use App\Models\LedgerEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountingController extends Controller
{
    /**
     * Create a new ledger entry
     */
    public function createEntry(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|uuid',
            'entry_type' => 'required|in:revenue,expense',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string',
            'reference_type' => 'nullable|string',
            'reference_id' => 'nullable|string',
        ]);

        $entry = LedgerEntry::create($request->all());

        return response()->json([
            'message' => 'Ledger entry created successfully',
            'entry' => $entry
        ], 201);
    }

    /**
     * Get financial history for a merchant
     */
    public function getHistory($tenant_id)
    {
        $entries = LedgerEntry::where('tenant_id', $tenant_id)
            ->latest()
            ->get();

        if ($entries->isEmpty()) {
            return response()->json(['message' => 'No accounting records found'], 404);
        }

        $summary = [
            'total_revenue' => $entries->where('entry_type', 'revenue')->sum('amount'),
            'total_expense' => $entries->where('entry_type', 'expense')->sum('amount'),
            'net_balance' => $entries->where('entry_type', 'revenue')->sum('amount') - $entries->where('entry_type', 'expense')->sum('amount'),
            'entries_count' => $entries->count()
        ];

        return response()->json([
            'summary' => $summary,
            'entries' => $entries
        ]);
    }

    /**
     * Get platform-level summary
     */
    public function getPlatformSummary()
    {
        $summary = [
            'platform_revenue' => LedgerEntry::where('entry_type', 'revenue')->sum('amount'),
            'platform_expense' => LedgerEntry::where('entry_type', 'expense')->sum('amount'),
            'total_transactions' => LedgerEntry::count(),
        ];

        return response()->json($summary);
    }
}
