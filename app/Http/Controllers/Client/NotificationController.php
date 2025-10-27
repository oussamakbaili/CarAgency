<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ClientNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Obtenir les notifications du client
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $limit = $request->get('limit', 10);
        
        $notifications = $user->clientNotifications()
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

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $user->clientNotifications()->unread()->count(),
            'total_count' => $user->clientNotifications()->count(),
        ]);
    }

    /**
     * Marquer une notification comme lue
     */
    public function markAsRead(ClientNotification $notification)
    {
        // Vérifier que la notification appartient à l'utilisateur
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à cette notification.');
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marquée comme lue.',
        ]);
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead()
    {
        Auth::user()->clientNotifications()->unread()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Toutes les notifications ont été marquées comme lues.',
        ]);
    }

    /**
     * Supprimer une notification
     */
    public function destroy(ClientNotification $notification)
    {
        // Vérifier que la notification appartient à l'utilisateur
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à cette notification.');
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification supprimée.',
        ]);
    }
}
