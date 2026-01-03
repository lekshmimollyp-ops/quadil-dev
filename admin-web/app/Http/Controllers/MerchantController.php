<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Redirect;

class MerchantController extends Controller
{
    protected $baseUrl;

    public function __construct() {
        $this->baseUrl = config('services.gateway.url') . '/tenant/api/v1';
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $token = session('remote_token');
        
        try {
            $response = Http::withToken($token)->timeout(5)
                ->get("{$this->baseUrl}/tenants");

            $merchants = $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            \Log::error("Failed to fetch merchants: " . $e->getMessage());
            $merchants = [];
        }

        return Inertia::render('Merchants/Index', [
            'merchants' => $merchants,
        ]);
    }

    /**
     * Display merchant hierarchy.
     */
    public function hierarchy(): Response
    {
        $token = session('remote_token');
        
        try {
            $response = Http::withToken($token)->timeout(5)
                ->get("{$this->baseUrl}/tenants/hierarchy");

            $hierarchy = $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            \Log::error("Failed to fetch merchant hierarchy: " . $e->getMessage());
            $hierarchy = [];
        }

        return Inertia::render('Merchants/Hierarchy', [
            'hierarchy' => $hierarchy,
        ]);
    }

    /**
     * Associate a freelancer with a merchant.
     */
    public function associateFreelancer(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|uuid',
            'freelancer_id' => 'required|integer',
        ]);

        $token = session('remote_token');

        $response = Http::withToken($token)->timeout(5)
            ->post("{$this->baseUrl}/tenants/associate-freelancer", $request->all());

        if ($response->successful()) {
            return back()->with('success', 'Agent associated successfully.');
        }

        return back()->withErrors(['message' => 'Failed to associate agent.']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $token = session('remote_token');
        
        try {
            $response = Http::withToken($token)->timeout(5)
                ->get("{$this->baseUrl}/tenants");
            $merchants = $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            $merchants = [];
        }

        return Inertia::render('Merchants/CreateEdit', [
            'merchant' => null,
            'merchants' => $merchants,
            'isEdit' => false,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $token = session('remote_token');

        // Convert empty parent_tenant_id to null
        $data = $request->all();
        if (empty($data['parent_tenant_id'])) {
            $data['parent_tenant_id'] = null;
        }

        $response = Http::withToken($token)->timeout(5)
            ->post("{$this->baseUrl}/tenants", $data);

        if ($response->successful()) {
            return Redirect::route('merchants.index')->with('success', 'Merchant created successfully.');
        }

        return back()->withErrors($response->json('errors', ['message' => 'Failed to create merchant.']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        $token = session('remote_token');
        
        try {
            $response = Http::withToken($token)->timeout(5)
                ->get("{$this->baseUrl}/tenants/{$id}");
            $merchant = $response->successful() ? $response->json() : null;

            $merchantsResponse = Http::withToken($token)->timeout(5)
                ->get("{$this->baseUrl}/tenants");
            $merchants = $merchantsResponse->successful() ? $merchantsResponse->json() : [];
        } catch (\Exception $e) {
            \Log::error("Failed to fetch merchant details: " . $e->getMessage());
            $merchant = null;
        }

        if (!$merchant) {
            return Redirect::route('merchants.index')->with('error', 'Merchant not found.');
        }

        return Inertia::render('Merchants/CreateEdit', [
            'merchant' => $merchant,
            'merchants' => $merchants ?? [],
            'isEdit' => true,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $token = session('remote_token');

        // Convert empty parent_tenant_id to null
        $data = $request->all();
        if (empty($data['parent_tenant_id'])) {
            $data['parent_tenant_id'] = null;
        }

        $response = Http::withToken($token)->timeout(5)
            ->put("{$this->baseUrl}/tenants/{$id}", $data);

        if ($response->successful()) {
            return Redirect::route('merchants.index')->with('success', 'Merchant updated successfully.');
        }

        return back()->withErrors($response->json('errors', ['message' => 'Failed to update merchant.']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $token = session('remote_token');

        $response = Http::withToken($token)->timeout(5)
            ->delete("{$this->baseUrl}/tenants/{$id}");

        if ($response->successful()) {
            return Redirect::route('merchants.index')->with('success', 'Merchant deleted successfully.');
        }

        return back()->withErrors(['message' => 'Failed to delete merchant.']);
    }
}
