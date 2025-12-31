<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Redirect;

class OrderController extends Controller
{
    protected $orderBaseUrl = 'http://127.0.0.1:8000/order/api/v1';
    protected $dispatchBaseUrl = 'http://127.0.0.1:8000/dispatch/api/v1';

    /**
     * Display a listing of orders.
     */
    public function index(Request $request): Response
    {
        $token = session('remote_token');
        
        try {
            $response = Http::withToken($token)->timeout(5)
                ->get("{$this->orderBaseUrl}/orders");

            $orders = $response->successful() ? $response->json() : ['data' => []];
        } catch (\Exception $e) {
            \Log::error("Failed to fetch orders: " . $e->getMessage());
            $orders = ['data' => []];
        }

        return Inertia::render('Orders/Index', [
            'orders' => $orders['data'] ?? $orders, // Handle potential pagination wrapper
        ]);
    }

    /**
     * Display the specified order.
     */
    public function show(string $id): Response
    {
        $token = session('remote_token');
        
        try {
            // Get Order Details
            $orderRes = Http::withToken($token)->timeout(5)
                ->get("{$this->orderBaseUrl}/orders/{$id}");
            $order = $orderRes->successful() ? $orderRes->json() : null;

            // Get Online Drivers for assignment
            $driversRes = Http::withToken($token)->timeout(5)
                ->get("{$this->dispatchBaseUrl}/drivers/online");
            $drivers = $driversRes->successful() ? $driversRes->json() : [];

        } catch (\Exception $e) {
            \Log::error("Failed to fetch order details/drivers: " . $e->getMessage());
            $order = null;
            $drivers = [];
        }

        if (!$order) {
            return Redirect::route('orders.index')->with('error', 'Order not found.');
        }

        return Inertia::render('Orders/Show', [
            'order' => $order,
            'availableDrivers' => $drivers,
        ]);
    }

    /**
     * Manual Dispatch: Assign a driver to an order.
     */
    public function assign(Request $request, string $id)
    {
        $token = session('remote_token');

        $response = Http::withToken($token)->timeout(5)
            ->post("{$this->dispatchBaseUrl}/assign", [
                'order_id' => $id,
                'driver_id' => $request->driver_id,
            ]);

        if ($response->successful()) {
            return Redirect::route('orders.show', $id)->with('success', 'Driver assigned successfully.');
        }

        return back()->withErrors(['message' => 'Failed to assign driver.']);
    }
}
