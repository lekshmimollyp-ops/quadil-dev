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

    /**
     * Get online drivers
     */
    public function getOnlineDrivers()
    {
        $drivers = Driver::where('status', 'online')->get();
        return response()->json($drivers);
    }
}
