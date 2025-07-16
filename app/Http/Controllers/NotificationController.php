<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index()
    {
        // Mock notifications data
        $notifications = collect([
            [
                'id' => 1,
                'type' => 'due_reminder',
                'title' => 'Book Due Tomorrow',
                'message' => 'Your book "To Kill a Mockingbird" is due tomorrow.',
                'created_at' => Carbon::now()->subHours(2),
                'read' => false,
                'icon' => 'fas fa-clock',
                'color' => 'text-orange-600'
            ],
            [
                'id' => 2,
                'type' => 'book_available',
                'title' => 'Reserved Book Available',
                'message' => 'Your reserved book "Harry Potter" is now available for pickup.',
                'created_at' => Carbon::now()->subHours(5),
                'read' => false,
                'icon' => 'fas fa-book',
                'color' => 'text-green-600'
            ],
            [
                'id' => 3,
                'type' => 'renewal_success',
                'title' => 'Book Renewed',
                'message' => 'You have successfully renewed "Charlotte\'s Web".',
                'created_at' => Carbon::now()->subDay(),
                'read' => true,
                'icon' => 'fas fa-check-circle',
                'color' => 'text-blue-600'
            ],
            [
                'id' => 4,
                'type' => 'system',
                'title' => 'Library Hours Update',
                'message' => 'Library will be closed on Monday for maintenance.',
                'created_at' => Carbon::now()->subDays(2),
                'read' => true,
                'icon' => 'fas fa-info-circle',
                'color' => 'text-gray-600'
            ]
        ]);

        $unreadCount = $notifications->where('read', false)->count();

        return view('notifications', compact('notifications', 'unreadCount'));
    }

    public function markAsRead($id)
    {
        // In real implementation, update notification in database
        // Notification::where('id', $id)->where('user_id', Auth::id())->update(['read' => true]);

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        // In real implementation, mark all user notifications as read
        // Notification::where('user_id', Auth::id())->update(['read' => true]);

        return redirect()->route('notifications')
            ->with('success', 'All notifications marked as read');
    }

    public function getUnreadCount()
    {
        // Mock unread count
        $unreadCount = 2;

        return response()->json(['count' => $unreadCount]);
    }
}