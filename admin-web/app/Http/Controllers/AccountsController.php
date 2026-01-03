<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Inertia\Response;

class AccountsController extends Controller
{
    protected $baseUrl;
    protected $tenantBaseUrl;

    public function __construct() {
        $this->baseUrl = config('services.gateway.url') . '/accounting/api/v1';
        $this->tenantBaseUrl = config('services.gateway.url') . '/tenant/api/v1';
    }

    /**
     * Display the financial ledger overview.
     */
    public function index(): Response
    {
        $token = session('remote_token');
        
        try {
            // Fetch Platform Financial Summary
            $summaryRes = Http::withToken($token)->timeout(5)
                ->get("{$this->baseUrl}/summary");
            $summary = $summaryRes->successful() ? $summaryRes->json() : [
                'platform_revenue' => 0,
                'platform_expense' => 0,
                'total_transactions' => 0
            ];
            
        } catch (\Exception $e) {
            \Log::error("Failed to fetch accounting data: " . $e->getMessage());
            $summary = ['platform_revenue' => 0, 'platform_expense' => 0, 'total_transactions' => 0];
        }

        return Inertia::render('Accounts/Ledgers/Index', [
            'summary' => $summary,
        ]);
    }

    /**
     * Display the ledger for a specific merchant.
     */
    public function merchantLedger(string $tenant_id): Response
    {
        $token = session('remote_token');

        try {
            $response = Http::withToken($token)->timeout(5)
                ->get("{$this->baseUrl}/entries/{$tenant_id}");

            $data = $response->successful() ? $response->json() : ['summary' => [], 'entries' => []];

            // Fetch Merchant Name
            $merchantRes = Http::withToken($token)->timeout(5)
                ->get("{$this->tenantBaseUrl}/tenants/{$tenant_id}");
            $merchantName = $merchantRes->successful() ? ($merchantRes->json()['name'] ?? 'Unknown Merchant') : 'Unknown Merchant';
        } catch (\Exception $e) {
            \Log::error("Failed to fetch merchant ledger: " . $e->getMessage());
            $data = ['summary' => [], 'entries' => []];
            $merchantName = 'Unknown Merchant';
        }

        return Inertia::render('Accounts/Ledgers/Show', [
            'tenant_id' => $tenant_id,
            'merchant_name' => $merchantName,
            'ledgerData' => $data,
        ]);
    }

    /**
     * Display the COD summary report.
     */
    public function codSummary(): Response
    {
        $token = session('remote_token');
        
        // Simulation data for COD Summary
        $codData = [
            [
                'agent_id' => '1',
                'agent_name' => 'John Doe',
                'collections' => 1250.50,
                'pending_remittance' => 450.00,
                'last_remittance' => '2023-12-28',
            ],
            [
                'agent_id' => '2',
                'agent_name' => 'Jane Smith',
                'collections' => 890.00,
                'pending_remittance' => 120.00,
                'last_remittance' => '2023-12-29',
            ],
            [
                'agent_id' => '3',
                'agent_name' => 'Bob Johnson',
                'collections' => 2100.00,
                'pending_remittance' => 0.00,
                'last_remittance' => '2023-12-30',
            ]
        ];

        return Inertia::render('Accounts/Reports/CODSummary', [
            'codData' => $codData,
        ]);
    }
}
