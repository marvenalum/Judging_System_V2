<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\NotificationPreference;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $unreadCount = $user->unreadNotifications()->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function mark_as_read($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return back()->with('success', 'Notification marked as read');
    }

    public function mark_all_as_read()
    {
        Auth::user()->unreadNotifications()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return back()->with('success', 'All notifications marked as read');
    }

    public function preferences()
    {
        $user = Auth::user();
        $preferences = $user->notificationPreferences ?? new NotificationPreference();

        return view('notifications.preferences', compact('preferences'));
    }

    public function update_preferences(Request $request)
    {
        $user = Auth::user();

        $preferences = $user->notificationPreferences ?? $user->notificationPreferences()->create([]);

        $preferences->update($request->validate([
            'email_judge_assignment' => 'boolean',
            'email_submission_deadline' => 'boolean',
            'email_scores_submitted' => 'boolean',
            'email_results_published' => 'boolean',
            'in_app_notifications' => 'boolean',
        ]));

        return back()->with('success', 'Notification preferences updated');
    }
}
