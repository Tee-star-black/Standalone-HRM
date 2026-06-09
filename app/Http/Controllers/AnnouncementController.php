<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\HrNotification;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        return view('announcements.index', [
            'announcements' => Announcement::where('status', 'published')
                ->orderByDesc('is_pinned')
                ->latest('published_at')
                ->latest()
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'is_pinned' => ['nullable'],
        ]);

        $announcement = Announcement::create([
            'created_by' => Auth::id(),
            'title' => $data['title'],
            'body' => $data['body'],
            'is_pinned' => $request->boolean('is_pinned'),
            'status' => 'published',
            'published_at' => now(),
        ]);

        AuditLogger::log(
            'announcement_created',
            'Created announcement: ' . $announcement->title,
            $announcement,
            [
                'announcement_id' => $announcement->id,
                'is_pinned' => $announcement->is_pinned,
            ]
        );

        User::query()
            ->select('id')
            ->chunk(100, function ($users) use ($announcement) {
                foreach ($users as $user) {
                    HrNotification::create([
                        'user_id' => $user->id,
                        'type' => 'info',
                        'title' => 'New company announcement',
                        'message' => $announcement->title,
                        'url' => route('announcements.index'),
                    ]);
                }
            });

        return redirect()
            ->route('announcements.index')
            ->with('status', 'Announcement published.');
    }

    public function destroy(Announcement $announcement)
    {
        AuditLogger::log(
            'announcement_deleted',
            'Deleted announcement: ' . $announcement->title,
            $announcement,
            [
                'announcement_id' => $announcement->id,
                'title' => $announcement->title,
            ]
        );

        $announcement->delete();

        return redirect()
            ->route('announcements.index')
            ->with('status', 'Announcement deleted.');
    }
}