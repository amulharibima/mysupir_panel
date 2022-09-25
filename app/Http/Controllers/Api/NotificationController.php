<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getUnredNotification()
    {
        $user = Auth::user();

        $unreadNotification = $user->unreadNotifications;

        return response()->json([
            'notifications' => $unreadNotification,
            'total' => count($unreadNotification)
        ]);
    }

    public function getAllNotification()
    {
        $user = Auth::user();

        $notifications = $user->notifications;

        return response()->json([
            'notifications' => $notifications,
            'total' => count($notifications)
        ]);
    }

    public function markNotificationAsRead($notificationId)
    {
        $user = Auth::user();

        $notification = $user->notifications;
        abort_if(empty($notification), 404);
        $selectedNotification = $notification->where('id', $notificationId)->first();

        if ($selectedNotification) {
            $selectedNotification->markAsRead();
        }

        return response()->json(['message' => 'ok']);
    }
}
