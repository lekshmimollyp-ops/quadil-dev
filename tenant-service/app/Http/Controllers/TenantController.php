<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    /**
     * Public Endpoint: Get Tenant Config by Domain/Slug
     */
    public function getConfig($slug)
    {
        $tenant = Tenant::where('domain', $slug)->where('is_active', true)->first();

        if (!$tenant) {
            return response()->json(['message' => 'Tenant not found'], 404);
        }

        return response()->json([
            'id' => $tenant->id,
            'name' => $tenant->name,
            'settings' => $tenant->settings,
        ]);
    }

    /**
     * Admin Endpoint: Onboard New Tenant
     */
    public function onboard(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:corporate,chain,single',
            'domain' => 'required|string|unique:tenants,domain',
            'settings' => 'nullable|array',
            'parent_tenant_id' => 'nullable|exists:tenants,id'
        ]);

        $tenant = Tenant::create([
            'name' => $request->name,
            'type' => $request->type,
            'domain' => $request->domain,
            'settings' => $request->settings ?? [],
            'parent_tenant_id' => $request->parent_tenant_id,
        ]);

        return response()->json([
            'message' => 'Tenant onboarded successfully',
            'tenant' => $tenant
        ], 201);
    }

    /**
     * Admin Endpoint: List all tenants
     */
    public function index()
    {
        $tenants = Tenant::orderBy('created_at', 'desc')->get();
        return response()->json($tenants);
    }

    /**
     * Admin Endpoint: Get single tenant details
     */
    public function show($id)
    {
        $tenant = Tenant::findOrFail($id);
        return response()->json($tenant);
    }

    /**
     * Admin Endpoint: Update tenant
     */
    public function update(Request $request, $id)
    {
        $tenant = Tenant::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string',
            'type' => 'sometimes|required|in:corporate,chain,single',
            'domain' => 'sometimes|required|string|unique:tenants,domain,' . $id,
            'settings' => 'nullable|array',
            'parent_tenant_id' => 'nullable|exists:tenants,id',
            'is_active' => 'sometimes|required|boolean'
        ]);

        $tenant->update($request->all());

        return response()->json([
            'message' => 'Tenant updated successfully',
            'tenant' => $tenant
        ]);
    }

    /**
     * Admin Endpoint: Toggle tenant status (soft delete)
     */
    public function destroy($id)
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->is_active = !$tenant->is_active;
        $tenant->save();

        $status = $tenant->is_active ? 'activated' : 'deactivated';
        return response()->json(['message' => "Tenant {$status} successfully"]);
    }

    /**
     * Admin Endpoint: Get Tenant Hierarchy
     */
    public function indexHierarchy()
    {
        $topLevel = Tenant::whereNull('parent_tenant_id')->with('children')->get();
        return response()->json($topLevel);
    }

    /**
     * Admin Endpoint: Associate Agent with Tenant
     */
    public function associateFreelancer(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|uuid|exists:tenants,id',
            'freelancer_id' => 'required|integer', // user_id from auth-service
        ]);

        $association = \App\Models\FreelancerAssociation::updateOrCreate(
            ['tenant_id' => $request->tenant_id, 'freelancer_user_id' => $request->freelancer_id],
            ['status' => 'approved']
        );

        return response()->json([
            'message' => 'Freelancer associated successfully',
            'association' => $association
        ]);
    }

    /**
     * Admin Endpoint: Get Tenant Associations
     */
    public function getAssociations($id)
    {
        $tenant = Tenant::findOrFail($id);
        return response()->json($tenant->freelancers()->get());
    }

    /**
     * Admin Endpoint: Get Freelancer's Merchant Associations
     */
    public function getFreelancerAssociations($freelancerUserId)
    {
        $associations = \App\Models\FreelancerAssociation::where('freelancer_user_id', $freelancerUserId)
            ->with('tenant')
            ->get();
        
        return response()->json($associations);
    }

    /**
     * Admin Endpoint: Remove Freelancer-Merchant Association
     */
    public function removeFreelancerAssociation(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|uuid',
            'freelancer_id' => 'required|integer',
        ]);

        $deleted = \App\Models\FreelancerAssociation::where('tenant_id', $request->tenant_id)
            ->where('freelancer_user_id', $request->freelancer_id)
            ->delete();

        if ($deleted) {
            return response()->json(['message' => 'Association removed successfully']);
        }

        return response()->json(['message' => 'Association not found'], 404);
    }
}
