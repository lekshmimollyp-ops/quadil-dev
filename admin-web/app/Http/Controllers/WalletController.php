<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Redirect;

class WalletController extends Controller
{
    protected $baseUrl = 'http://127.0.0.1:8000/wallet/api/v1';
    protected $tenantBaseUrl = 'http://127.0.0.1:8000/tenant/api/v1';

    /**
     * Display a listing of merchant wallets.
     */
    public function index(): Response
    {
        $token = session('remote_token');
        
        try {
            // We need the list of merchants first
            $merchantsRes = Http::withToken($token)->timeout(5)
                ->get("{$this->tenantBaseUrl}/tenants");
            $merchants = $merchantsRes->successful() ? $merchantsRes->json() : [];

            // For each merchant, fetch their balance
            $wallets = [];
            foreach ($merchants as $merchant) {
                $balanceRes = Http::withToken($token)->timeout(5)
                    ->get("{$this->baseUrl}/balance/{$merchant['id']}");
                
                if ($balanceRes->successful()) {
                    $wallet = $balanceRes->json();
                    $wallet['merchant_name'] = $merchant['name'];
                    $wallets[] = $wallet;
                }
            }

        } catch (\Exception $e) {
            \Log::error("Failed to fetch wallet data: " . $e->getMessage());
            $wallets = [];
        }

        return Inertia::render('Accounts/Wallets/Index', [
            'wallets' => $wallets,
        ]);
    }

    /**
     * Top up a merchant's wallet.
     */
    public function topup(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|uuid',
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string',
        ]);

        $token = session('remote_token');

        $response = Http::withToken($token)->timeout(5)
            ->post("{$this->baseUrl}/topup", $request->all());

        if ($response->successful()) {
            return Redirect::route('wallets.index')->with('success', 'Wallet topped up successfully.');
        }

        return back()->withErrors(['message' => 'Failed to top up wallet.']);
    }

    /**
     * Update merchant credit limit.
     */
    public function updateCreditLimit(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|uuid',
            'credit_limit' => 'required|numeric|min:0',
        ]);

        $token = session('remote_token');

        $response = Http::withToken($token)->timeout(5)
            ->patch("{$this->baseUrl}/credit-limit", $request->all());

        if ($response->successful()) {
            return back()->with('success', 'Credit limit updated successfully.');
        }

        return back()->withErrors(['message' => 'Failed to update credit limit.']);
    }
}
