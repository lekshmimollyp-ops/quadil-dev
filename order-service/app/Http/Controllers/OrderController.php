<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Create a new booking
     */
    public function createOrder(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|uuid',
            'user_id' => 'required|integer',
            'pickup_details' => 'required|array',
            'delivery_details' => 'required|array',
            'parcel_details' => 'required|array',
            'total_amount' => 'nullable|numeric',
        ]);

        $order = Order::create([
            'tenant_id' => $request->tenant_id,
            'user_id' => $request->user_id,
            'pickup_details' => $request->pickup_details,
            'delivery_details' => $request->delivery_details,
            'parcel_details' => $request->parcel_details,
            'status' => 'pending',
            'total_amount' => $request->total_amount ?? 0.00,
        ]);

        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order
        ], 201);
    }

    /**
     * Track Order Status
     */
    public function show($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json($order);
    }

    public function getSummary()
    {
        $count = Order::count();
        // Mocking change for now
        return response()->json([
            'total_count' => number_format($count),
            'change' => '+5.2%',
            'trend' => 'up'
        ]);
    }

    public function getRecent()
    {
        $orders = Order::orderBy('created_at', 'desc')->take(10)->get();
        
        $data = $orders->map(function($order) {
            return [
                'id' => 'ORD-' . $order->id,
                'customer' => 'Client #' . $order->user_id,
                'status' => ucfirst($order->status),
                'amount' => '$' . number_format($order->total_amount, 2),
                'time' => $order->created_at->diffForHumans(),
            ];
        });

        return response()->json(['data' => $data]);
    }

    /**
     * Admin Endpoint: List all orders
     */
    public function index()
    {
        $orders = Order::orderBy('created_at', 'desc')->paginate(15);
        return response()->json($orders);
    }
}
