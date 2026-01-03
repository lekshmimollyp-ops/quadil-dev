<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Assignment;
use Illuminate\Http\Request;

class DispatchController extends Controller
{
    /**
     * Register a new driver profile
     */
    public function registerDriver(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|unique:drivers,user_id',
            'vehicle_type' => 'required|string',
            'license_number' => 'nullable|string',
        ]);

        $driver = Driver::create([
            'user_id' => $request->user_id,
            'vehicle_type' => $request->vehicle_type,
            'license_number' => $request->license_number,
            'status' => 'offline',
        ]);

        return response()->json([
            'message' => 'Driver profile created',
            'driver' => $driver
        ], 201);
    }

    /**
     * Update driver availability status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:online,busy,offline',
        ]);

        $driver = Driver::findOrFail($id);
        $driver->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Driver status updated',
            'driver' => $driver
        ]);
    }

    /**
     * Assign a driver to an order
     */
    public function assignOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|uuid',
            'driver_id' => 'required|uuid',
        ]);

        $assignment = Assignment::create([
            'order_id' => $request->order_id,
            'driver_id' => $request->driver_id,
            'status' => 'assigned',
        ]);

        // In a real scenario, we would also update the driver status to 'busy'
        Driver::where('id', $request->driver_id)->update(['status' => 'busy']);

        return response()->json([
            'message' => 'Driver assigned to order',
            'assignment' => $assignment
        ]);
    }

    public function getOnlineDrivers()
    {
        $drivers = Driver::where('status', 'online')->get();
        return response()->json($drivers);
    }

    // --- Driver Workflow Endpoints ---

    /**
     * Get available jobs for a driver
     */
    public function getAvailableJobs(Request $request)
    {
        // For simple demo, return assignments in 'assigned' state for this driver
        // In real marketplace, this would query unassigned orders in driver's zone.
        $driverId = $request->query('driver_id'); 
        
        $jobs = Assignment::where('driver_id', $driverId)
            ->whereIn('status', ['assigned', 'picked_up'])
            ->get();
            
        return response()->json($jobs);
    }

    /**
     * Driver accepts/confirms a job
     */
    public function acceptJob($id)
    {
        $assignment = Assignment::findOrFail($id);
        $assignment->update(['status' => 'accepted']);
        return response()->json(['message' => 'Job accepted', 'status' => 'accepted']);
    }

    /**
     * Driver picks up current job
     */
    public function pickupJob($id)
    {
        $assignment = Assignment::findOrFail($id);
        $assignment->update(['status' => 'picked_up']);
        // Ideally emit event to OrderService to update Order status
        return response()->json(['message' => 'Job picked up', 'status' => 'picked_up']);
    }

    /**
     * Driver completes/delivers job
     */
    public function completeJob($id)
    {
        $assignment = Assignment::findOrFail($id);
        $assignment->update(['status' => 'delivered']);
        
        // Free up the driver
        Driver::where('id', $assignment->driver_id)->update(['status' => 'online']);

        return response()->json(['message' => 'Job completed', 'status' => 'delivered']);
    }
}
