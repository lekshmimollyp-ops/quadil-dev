<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Redirect;

class AgentController extends Controller
{
    protected $baseUrl = 'http://127.0.0.1:8000/agent/api/v1';

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $token = session('remote_token');
        
        try {
            $response = Http::withToken($token)->timeout(5)
                ->get("{$this->baseUrl}/agents");
            $agents = $response->successful() ? $response->json() : [];

            // Fetch merchants for association modal
            $tenantBaseUrl = 'http://127.0.0.1:8000/tenant/api/v1';
            $merchantsRes = Http::withToken($token)->timeout(5)
                ->get("{$tenantBaseUrl}/tenants");
            $merchants = $merchantsRes->successful() ? $merchantsRes->json() : [];

            // Fetch associations for each agent
            foreach ($agents as &$agent) {
                try {
                    $assocRes = Http::withToken($token)->timeout(5)
                        ->get("{$tenantBaseUrl}/freelancers/{$agent['user_id']}/associations");
                    $agent['associations'] = $assocRes->successful() ? $assocRes->json() : [];
                } catch (\Exception $e) {
                    $agent['associations'] = [];
                }
            }
        } catch (\Exception $e) {
            \Log::error("Failed to fetch agents/merchants: " . $e->getMessage());
            $agents = [];
            $merchants = [];
        }

        return Inertia::render('Agents/Index', [
            'agents' => $agents,
            'merchants' => $merchants,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Response
    {
        $token = session('remote_token');
        
        try {
            $response = Http::withToken($token)->timeout(5)
                ->get("{$this->baseUrl}/agents/{$id}");

            $agent = $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            \Log::error("Failed to fetch agent details: " . $e->getMessage());
            $agent = null;
        }

        if (!$agent) {
            return Redirect::route('agents.index')->with('error', 'Agent not found.');
        }

        return Inertia::render('Agents/Show', [
            'agent' => $agent,
        ]);
    }

    /**
     * Update agent status.
     */
    public function updateStatus(Request $request, string $id)
    {
        $token = session('remote_token');

        $response = Http::withToken($token)->timeout(5)
            ->patch("{$this->baseUrl}/agents/{$id}/status", [
                'status' => $request->status,
            ]);

        if ($response->successful()) {
            return back()->with('success', 'Agent status updated successfully.');
        }

        return back()->withErrors(['message' => 'Failed to update agent status.']);
    }

    /**
     * Approve/Activate agent profile.
     */
    public function approve(string $id)
    {
        $token = session('remote_token');

        $response = Http::withToken($token)->timeout(5)
            ->patch("{$this->baseUrl}/agents/{$id}/approve");

        if ($response->successful()) {
            return back()->with('success', 'Agent approved successfully.');
        }

        return back()->withErrors(['message' => 'Failed to approve agent.']);
    }

    /**
     * Associate agent with merchant.
     */
    public function associateWithMerchant(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|uuid',
            'freelancer_id' => 'required|integer',
        ]);

        $token = session('remote_token');
        $tenantBaseUrl = 'http://127.0.0.1:8000/tenant/api/v1';

        $response = Http::withToken($token)->timeout(5)
            ->post("{$tenantBaseUrl}/tenants/associate-freelancer", $request->all());

        if ($response->successful()) {
            return back()->with('success', 'Agent associated with merchant successfully.');
        }

        return back()->withErrors(['message' => 'Failed to associate agent with merchant.']);
    }

    /**
     * Remove agent association from merchant.
     */
    public function disassociateFromMerchant(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|uuid',
            'freelancer_id' => 'required|integer',
        ]);

        $token = session('remote_token');
        $tenantBaseUrl = 'http://127.0.0.1:8000/tenant/api/v1';

        // Use POST since we're sending data in the body
        $response = Http::withToken($token)->timeout(5)
            ->post("{$tenantBaseUrl}/tenants/disassociate-freelancer", $request->all());

        if ($response->successful()) {
            return back()->with('success', 'Agent disassociated from merchant successfully.');
        }

        return back()->withErrors(['message' => 'Failed to disassociate agent from merchant.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $token = session('remote_token');

        $response = Http::withToken($token)->timeout(5)
            ->delete("{$this->baseUrl}/agents/{$id}");

        if ($response->successful()) {
            return Redirect::route('agents.index')->with('success', 'Agent status updated successfully.');
        }

        return back()->withErrors(['message' => 'Failed to update agent status.']);
    }
}
