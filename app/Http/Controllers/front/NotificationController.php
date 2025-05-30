<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function index()
    {
        return response()->json([
            'notifications' => auth()->user()->notifications()->latest()->take(10)->get(),
            'unread_count' => auth()->user()->unreadNotifications()->count(),
        ]);
    }
    public function allNotification()
    {
        $user = auth()->user();

        $notifications = $user->notifications()
            ->latest() // Order by latest first
            ->limit(20) // Get only the latest 10
            ->get()->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => class_basename($notification->type),
                    'data' => $notification->data,
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at->diffForHumans(),
                ];
            });


        return Inertia::render('Notifications/AllNotifications', [
            'notifications' => $notifications,
            'unread_count' => $user->unreadNotifications()->count(),
        ]);
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }
}
