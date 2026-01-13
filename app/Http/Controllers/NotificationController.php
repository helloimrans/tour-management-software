<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{

    public function getAllNotifications()
    {
        return view('layouts.admin.includes.notifications');
    }

    public function markAsRead($notificationId)
    {
        $notification = DatabaseNotification::findOrFail($notificationId);
        $notification->markAsRead();
        return response()->json(['success' => true, 'message' => 'Mark as read removed successfully', 'data' => $notification]);
    }

    public function markAllAsRead()
    {
        $notification = Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true, 'message' => 'Mark all as read successfully', 'data' => $notification]);
    }

    public function remove($notificationId)
    {
        $notification = DatabaseNotification::findOrFail($notificationId);
        $notification->delete();
        return response()->json(['success' => true, 'message' => 'Notification removed successfully', 'data' => $notification]);
    }
}
