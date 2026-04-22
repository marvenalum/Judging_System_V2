<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;
use App\Models\JudgeEventAssignment;

class UserController extends Controller
{
    public function index() {
        $user = auth()->user();
        if ($user->role === 'judge') {
            $users = User::where('role', 'participant')->get();
        } else {
            $users = User::all();
        }
        return view('admin.users', compact('users'));
    }

    public function create() {
        return view('admin.users.create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,judge,participant',
            'status' => 'nullable|in:active,inactive',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['status'] = $validated['status'] ?? 'active';

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user) {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user) {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,judge,participant',
            'status' => 'nullable|in:active,inactive',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }
        $validated['status'] = $validated['status'] ?? $user->status;

        $user->update($validated);

        $authUser = auth()->user();
        $route = $authUser->role === 'judge' ? 'judge.users.index' : 'admin.users.index';

        return redirect()->route($route)->with('success', 'User updated successfully.');
    }

    public function destroy(User $user) {
        $user->delete();

        $authUser = auth()->user();
        $route = $authUser->role === 'judge' ? 'judge.users.index' : 'admin.users.index';

        return redirect()->route($route)->with('success', 'User deleted successfully.');
    }

    public function toggleStatus(User $user)
    {
        $authUser = auth()->user();
        
        // Prevent judge from toggling, or toggling self
        if ($authUser->role === 'judge' || $authUser->id === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot toggle status for this user.');
        }

        // Prevent toggling other admins
        if ($user->role === 'admin' && $authUser->role !== 'admin') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot toggle admin status.');
        }

        $newStatus = $user->status === 'active' ? 'inactive' : 'active';
        $user->update(['status' => $newStatus]);

        return redirect()->route('admin.users.index')
            ->with('success', "User status changed to " . ucfirst($newStatus) . ".");
    }

    public function judges()
    {
        $judges = User::where('role', 'judge')
            ->with('judgeAssignments.event')
            ->paginate(15);
        return view('admin.judges', compact('judges'));
    }

    /**
     * Show form to assign events to judge
     */
    public function assignEvents(User $judge)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        if ($judge->role !== 'judge') {
            return redirect()->route('admin.judge.index')
                ->with('error', 'Can only assign events to judges.');
        }

        // More inclusive query - show all non-archived events
        $events = Event::whereNotNull('event_status')
            ->where('event_status', '!=', 'archived')
            ->orderBy('event_status', 'desc')
            ->orderBy('created_at', 'desc')
            ->get(['id', 'event_name', 'event_status', 'start_date', 'end_date']);

        // Debug info
        \Log::info('AssignEvents Debug', [
            'judge_id' => $judge->id,
            'judge_name' => $judge->name,
            'total_events' => Event::count(),
            'active_events' => $events->count(),
            'judge_assignments_count' => $judge->judgeAssignments()->count()
        ]);

        $currentAssignments = $judge->judgeAssignments()->pluck('event_id')->toArray();

        return view('admin.judges.assign-events', compact('judge', 'events', 'currentAssignments'));
    }

    /**
     * Store judge-event assignments
     */
    public function storeAssignment(Request $request, User $judge)
    {
        $request->validate([
            'event_ids' => 'required|array',
            'event_ids.*' => 'exists:events,id'
        ]);

        foreach ($request->event_ids as $eventId) {
            JudgeEventAssignment::updateOrCreate(
                [
                    'judge_id' => $judge->id,
                    'event_id' => $eventId
                ],
                ['status' => 'active']
            );

            // Send notification to judge
            $event = \App\Models\Event::find($eventId);
            \App\Services\NotificationService::notifyJudgeAssignment($judge, $event);

            // Generate anonymous code if anonymous judging is enabled
            if ($event->anonymous_judging) {
                \App\Services\AnonymousJudgingService::generateAnonymousCode($event, $judge);
            }
        }

        return redirect()->route('admin.judges.assignments', $judge)
            ->with('success', 'Judge assigned to selected events.');
    }

    /**
     * Show judge's current event assignments
     */
    public function assignments(User $judge)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $assignments = $judge->judgeAssignments()
            ->with('event')
            ->paginate(10);

        return view('admin.judges.assignments', compact('judge', 'assignments'));
    }

    /**
     * Remove judge-event assignment
     */
    public function removeAssignment(JudgeEventAssignment $assignment)
    {
        $judge = $assignment->judge;
        
        $assignment->delete();

        return redirect()->route('admin.judges.assignments', $judge)
            ->with('success', 'Assignment removed.');
    }

    /**
     * Show form to assign participants to judge
     */
    public function assignParticipants(User $judge)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        if ($judge->role !== 'judge') {
            return redirect()->route('admin.judge.index')
                ->with('error', 'Can only assign participants to judges.');
        }

        // Get events assigned to this judge
        $assignedEventIds = $judge->judgeAssignments()->pluck('event_id');
        $events = \App\Models\Event::whereIn('id', $assignedEventIds)->get(['id', 'event_name']);

        // Get participants for those events
        $participants = \App\Models\User::where('role', 'participant')
            ->whereHas('submissions', function ($q) use ($assignedEventIds) {
                $q->whereIn('event_id', $assignedEventIds);
            })
            ->get(['id', 'name', 'email']);

        // Group participants by event
        $participantsByEvent = [];
        foreach ($events as $event) {
            $eventParticipants = $participants->filter(function ($p) use ($event) {
                return $p->submissions()->where('event_id', $event->id)->exists();
            });
            $participantsByEvent[$event->id] = $eventParticipants;
        }

        $currentAssignments = $judge->judgeParticipantAssignments()->pluck('participant_id', 'event_id')->toArray();

        return view('admin.judges.assign-participants', compact('judge', 'events', 'participantsByEvent', 'currentAssignments'));
    }

    /**
     * Store judge-participant assignments
     */
    public function storeParticipantAssignment(Request $request, User $judge)
    {
        $request->validate([
            'assignments' => 'required|array',
            'assignments.*.event_id' => 'exists:events,id',
            'assignments.*.participant_id' => 'exists:users,id'
        ]);

        foreach ($request->assignments as $assignmentData) {
            \App\Models\JudgeParticipantAssignment::updateOrCreate(
                [
                    'judge_id' => $judge->id,
                    'event_id' => $assignmentData['event_id'],
                    'participant_id' => $assignmentData['participant_id']
                ],
                ['status' => 'active']
            );
        }

        return redirect()->route('admin.judges.participant-assignments', $judge)
            ->with('success', 'Judge assigned to selected participants.');
    }

    /**
     * Show judge's current participant assignments
     */
    public function participantAssignments(User $judge)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $assignments = $judge->judgeParticipantAssignments()
            ->with(['event', 'participant'])
            ->paginate(10);

        return view('admin.judges.participant-assignments', compact('judge', 'assignments'));
    }

    /**
     * Remove judge-participant assignment
     */
    public function removeParticipantAssignment(\App\Models\JudgeParticipantAssignment $assignment)
    {
        $judge = $assignment->judge;
        
        $assignment->delete();

        return redirect()->route('admin.judges.participant-assignments', $judge)
            ->with('success', 'Participant assignment removed.');
    }
}



