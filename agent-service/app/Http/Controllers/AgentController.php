<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    /**
     * Register a new agent profile
     */
    public function registerAgent(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|unique:agents,user_id',
        ]);

        $agent = Agent::create([
            'user_id' => $request->user_id,
            'current_status' => 'offline',
        ]);

        return response()->json([
            'message' => 'Agent profile created',
            'agent' => $agent
        ], 201);
    }

    /**
     * Add a vehicle to an agent
     */
    public function addVehicle(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|uuid|exists:agents,id',
            'vehicle_type' => 'required|string',
            'plate_number' => 'required|string|unique:vehicles,plate_number',
        ]);

        $vehicle = Vehicle::create([
            'agent_id' => $request->agent_id,
            'vehicle_type' => $request->vehicle_type,
            'plate_number' => $request->plate_number,
        ]);

        return response()->json([
            'message' => 'Vehicle registered successfully',
            'vehicle' => $vehicle
        ], 201);
    }

    /**
     * Get agent with vehicles
     */
    public function show($id)
    {
        $agent = Agent::with('vehicles')->find($id);

        if (!$agent) {
            return response()->json(['message' => 'Agent not found'], 404);
        }

        return response()->json($agent);
    }

    /**
     * Toggle agent status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:online,away,offline',
        ]);

        $agent = Agent::findOrFail($id);
        $agent->update(['current_status' => $request->status]);

        return response()->json([
            'message' => 'Agent status updated',
            'agent' => $agent
        ]);
    }

    /**
     * Admin Endpoint: List all agents with vehicles
     */
    public function index()
    {
        $agents = Agent::with('vehicles')->orderBy('created_at', 'desc')->get();
        return response()->json($agents);
    }

    /**
     * Admin Endpoint: Approve/Activate Agent
     */
    public function approve($id)
    {
        $agent = Agent::findOrFail($id);
        $agent->update(['is_active' => true]);

        return response()->json([
            'message' => 'Agent approved successfully',
            'agent' => $agent
        ]);
    }

    /**
     * Admin Endpoint: Toggle agent status (soft delete)
     */
    public function destroy($id)
    {
        $agent = Agent::findOrFail($id);
        $agent->is_active = !$agent->is_active;
        $agent->save();

        $status = $agent->is_active ? 'activated' : 'deactivated';
        return response()->json(['message' => "Agent {$status} successfully"]);
    }
}
