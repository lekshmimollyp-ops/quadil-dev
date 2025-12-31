<?php

namespace App\Http\Controllers;

use App\Models\Pod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PodController extends Controller
{
    /**
     * Generate an OTP for an order
     */
    public function generateOtp(Request $request)
    {
        $request->validate([
            'order_id' => 'required|uuid',
            'agent_id' => 'required|uuid',
        ]);

        // Delete any existing unverified OTP for this order
        Pod::where('order_id', $request->order_id)
            ->where('type', 'otp')
            ->where('is_verified', false)
            ->delete();

        // Generate a 4-digit OTP
        $otp = (string) rand(1000, 9999);

        $pod = Pod::create([
            'order_id' => $request->order_id,
            'agent_id' => $request->agent_id,
            'type' => 'otp',
            'value' => $otp,
            'is_verified' => false,
        ]);

        return response()->json([
            'message' => 'OTP generated successfully',
            'otp' => $otp, // In a real system, this would be sent via SMS/Notification Service
            'pod_id' => $pod->id
        ], 201);
    }

    /**
     * Verify OTP and complete POD
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'order_id' => 'required|uuid',
            'otp' => 'required|string|size:4',
        ]);

        $pod = Pod::where('order_id', $request->order_id)
            ->where('type', 'otp')
            ->where('value', $request->otp)
            ->where('is_verified', false)
            ->first();

        if (!$pod) {
            return response()->json(['message' => 'Invalid or expired OTP'], 400);
        }

        $pod->update([
            'is_verified' => true,
            'captured_at' => now(),
        ]);

        return response()->json([
            'message' => 'Order verified successfully',
            'pod' => $pod
        ]);
    }

    /**
     * Capture Signature or Photo POD
     */
    public function capturePod(Request $request)
    {
        $request->validate([
            'order_id' => 'required|uuid',
            'agent_id' => 'required|uuid',
            'type' => 'required|in:signature,photo',
            'value' => 'required|string', // Base64 or File Path
        ]);

        $pod = Pod::create([
            'order_id' => $request->order_id,
            'agent_id' => $request->agent_id,
            'type' => $request->type,
            'value' => $request->value,
            'is_verified' => true,
            'captured_at' => now(),
        ]);

        return response()->json([
            'message' => ucfirst($request->type) . ' captured successfully',
            'pod' => $pod
        ], 201);
    }

    /**
     * Get POD details for an order
     */
    public function show($order_id)
    {
        $pods = Pod::where('order_id', $order_id)->get();

        if ($pods->isEmpty()) {
            return response()->json(['message' => 'No POD records found'], 404);
        }

        return response()->json($pods);
    }
}
