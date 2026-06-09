<?php

namespace App\Http\Controllers;

use App\Models\HrNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = HrNotification::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('notifications.index', [
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead(HrNotification $notification)
    {
        if ((int) $notification->user_id !== (int) Auth::id()) {
            abort(403);
        }

        $notification->update([
            'read_at' => now(),
        ]);

        return redirect($notification->url ?: route('notifications.index'));
    }

    public function markAllAsRead()
    {
        HrNotification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('status', 'All notifications marked as read.');
    }
}