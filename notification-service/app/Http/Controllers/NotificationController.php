<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Send (log) a new notification
     */
    public function send(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'type' => 'required|in:sms,whatsapp,push',
            'content' => 'required|string',
            'reference_id' => 'nullable|string',
        ]);

        // Simulating external API call with 90% success rate
        $status = (rand(1, 100) <= 90) ? 'sent' : 'failed';

        $notification = Notification::create([
            'user_id' => $request->user_id,
            'type' => $request->type,
            'content' => $request->content,
            'status' => $status,
            'reference_id' => $request->reference_id,
        ]);

        return response()->json([
            'message' => $status === 'sent' ? 'Notification sent successfully' : 'Notification delivery failed',
            'notification' => $notification
        ], $status === 'sent' ? 200 : 500);
    }

    /**
     * Get notification history for a user
     */
    public function history($user_id)
    {
        $history = Notification::where('user_id', $user_id)
            ->latest()
            ->get();

        if ($history->isEmpty()) {
            return response()->json(['message' => 'No notification history found'], 404);
        }

        return response()->json($history);
    }
}
