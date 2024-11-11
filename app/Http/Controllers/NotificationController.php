<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markAsRead($notificationId)
    {
        $notification = auth()->user()->notifications()->findOrFail($notificationId);
        $notification->markAsRead();
        return redirect()->back();
    }
    public function markAllAsRead()
    {
        $unreadNotifications = auth()->user()->unreadNotifications;
        $unreadNotifications->markAsRead();
        return redirect()->back();
    }

    public function fetchNotifications()
    {
        $user = auth()->user();
        $notifications = $user->notifications()->paginate(5);
        $unreadCount = auth()->user()->unreadNotifications->count();
        return response()->json(['notifications' => $notifications, 'unread_count' => $unreadCount], 200);
    }
}
