<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get notifications for the authenticated agency.
     */
    public function index(Request $request)
    {
        $agency = auth()->user()->agency;
        
        if (!$agency) {
            return response()->json([
                'notifications' => [],
                'unread_count' => 0,
                'total_count' => 0
            ]);
        }

        $limit = $request->get('limit', 10);
        
        $notifications = Notification::where('agency_id', $agency->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'icon' => $notification->icon,
                    'icon_svg' => $notification->icon_svg,
                    'icon_color_class' => $notification->icon_color_class,
                    'action_url' => $notification->action_url,
                    'is_read' => $notification->is_read,
                    'time_ago' => $notification->time_ago,
                    'created_at' => $notification->created_at->format('Y-m-d H:i:s'),
                ];
            });

        $unread_count = Notification::where('agency_id', $agency->id)
            ->unread()
            ->count();
            
        $total_count = Notification::where('agency_id', $agency->id)
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unread_count,
            'total_count' => $total_count
        ]);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead($id)
    {
        $agency = auth()->user()->agency;
        
        if (!$agency) {
            return response()->json(['success' => false], 403);
        }

        $notification = Notification::where('agency_id', $agency->id)
            ->where('id', $id)
            ->first();

        if (!$notification) {
            return response()->json(['success' => false], 404);
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        $agency = auth()->user()->agency;
        
        if (!$agency) {
            return response()->json(['success' => false], 403);
        }

        Notification::where('agency_id', $agency->id)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }

    /**
     * Delete a notification.
     */
    public function destroy($id)
    {
        $agency = auth()->user()->agency;
        
        if (!$agency) {
            return response()->json(['success' => false], 403);
        }

        $notification = Notification::where('agency_id', $agency->id)
            ->where('id', $id)
            ->first();

        if (!$notification) {
            return response()->json(['success' => false], 404);
        }

        $notification->delete();

        return response()->json(['success' => true]);
    }
}

