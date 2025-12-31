<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    /**
     * Store a live GPS ping from an agent
     */
    public function ping(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|uuid',
            'order_id' => 'nullable|uuid',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $location = Location::create($request->all());

        return response()->json([
            'message' => 'Location ping received',
            'location' => $location
        ], 201);
    }

    /**
     * Get the latest known location of an agent
     */
    public function getLatest($agent_id)
    {
        $location = Location::where('agent_id', $agent_id)
            ->latest()
            ->first();

        if (!$location) {
            return response()->json(['message' => 'No location data found'], 404);
        }

        return response()->json($location);
    }

    /**
     * Verify if agent is within geofence (50m radius)
     */
    public function checkGeofence(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|uuid',
            'target_lat' => 'required|numeric',
            'target_lng' => 'required|numeric',
        ]);

        $current = Location::where('agent_id', $request->agent_id)
            ->latest()
            ->first();

        if (!$current) {
            return response()->json(['message' => 'No location history for agent'], 404);
        }

        $distance = $this->calculateDistance(
            $current->latitude,
            $current->longitude,
            $request->target_lat,
            $request->target_lng
        );

        $isWithin = $distance <= 0.05; // 0.05 KM = 50 Meters

        return response()->json([
            'agent_id' => $request->agent_id,
            'distance_meters' => round($distance * 1000, 2),
            'within_50m' => $isWithin,
            'message' => $isWithin ? 'Agent is on-site' : 'Agent is too far away'
        ]);
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // KM

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
