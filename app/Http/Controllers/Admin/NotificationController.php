<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;

class NotificationController extends Controller
{
    /**
     * Get notifications for admin
     */
    public function index(Request $request)
    {
        $admin = auth()->user();
        
        $query = Notification::forAdmin($admin->id)
            ->with(['agency', 'client', 'rental', 'transaction'])
            ->recent(50);

        // Filter by category if provided
        if ($request->has('category') && $request->category !== 'all') {
            $query->byCategory($request->category);
        }

        // Filter by read status if provided
        if ($request->has('read') && $request->read !== 'all') {
            if ($request->read === 'unread') {
                $query->unread();
            } else {
                $query->where('is_read', true);
            }
        }

        $notifications = $query->get();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'unread_count' => Notification::forAdmin($admin->id)->unread()->count(),
            ]);
        }

        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadCount()
    {
        $admin = auth()->user();
        $count = Notification::forAdmin($admin->id)->unread()->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $admin = auth()->user();
        
        $notification = Notification::forAdmin($admin->id)
            ->findOrFail($id);

        $notification->markAsRead();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Notification marquée comme lue.');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $admin = auth()->user();
        
        Notification::forAdmin($admin->id)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    /**
     * Delete notification
     */
    public function destroy($id)
    {
        $admin = auth()->user();
        
        $notification = Notification::forAdmin($admin->id)
            ->findOrFail($id);

        $notification->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Notification supprimée.');
    }

    /**
     * Clear all notifications
     */
    public function clearAll()
    {
        $admin = auth()->user();
        
        Notification::forAdmin($admin->id)->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Toutes les notifications ont été supprimées.');
    }

    /**
     * Get notification statistics
     */
    public function getStats()
    {
        $admin = auth()->user();
        
        $stats = [
            'total' => Notification::forAdmin($admin->id)->count(),
            'unread' => Notification::forAdmin($admin->id)->unread()->count(),
            'by_category' => [
                'support' => Notification::forAdmin($admin->id)->byCategory('support')->count(),
                'reservation' => Notification::forAdmin($admin->id)->byCategory('reservation')->count(),
                'payment' => Notification::forAdmin($admin->id)->byCategory('payment')->count(),
                'agency' => Notification::forAdmin($admin->id)->byCategory('agency')->count(),
            ],
            'by_priority' => [
                'urgent' => Notification::forAdmin($admin->id)->where('priority', 'urgent')->count(),
                'high' => Notification::forAdmin($admin->id)->where('priority', 'high')->count(),
                'medium' => Notification::forAdmin($admin->id)->where('priority', 'medium')->count(),
                'low' => Notification::forAdmin($admin->id)->where('priority', 'low')->count(),
            ],
        ];

        return response()->json($stats);
    }
}
