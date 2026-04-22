<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;

/**
 * @property-read \App\Models\User $user
 */
class AdminController extends Controller
{
    public function dashboard() {
        return view('admin.dashboard');
    }

    public function users() {
        return view('admin.users');
    }

    public function settings() {
        return view('admin.settings');
    }



    public function index() {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if ($user && $user->role === 'judge') {
            $events = $user->judgeAssignments()
                ->with('event')
                ->get()
                ->pluck('event')
                ->filter();
            return view('judge.events.index', compact('events'));
        } else {
            $events = Event::paginate(15);
            return view('admin.events', compact('events'));
        }
    }

    public function show(Event $event) {
        return view('admin.events.show', compact('event'));
    }

    public function create() {
        return view('admin.events.create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'event_description' => 'required|string',
            'event_type' => 'required|in:pageant,contest,competition',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'event_status' => 'required|in:upcoming,ongoing,completed',
            'anonymous_judging' => 'nullable|boolean',
            'anonymity_level' => 'nullable|in:full,partial',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['anonymous_judging'] = $request->has('anonymous_judging');

        Event::create($validated);

        return redirect()->route('admin.event.index')->with('success', 'Event created successfully.');
    }

    public function edit(Event $event) {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event) {
        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'event_description' => 'required|string',
            'event_type' => 'required|in:pageant,contest,competition',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'event_status' => 'required|in:upcoming,ongoing,completed',
            'anonymous_judging' => 'nullable|boolean',
            'anonymity_level' => 'nullable|in:full,partial',
        ]);

        $validated['anonymous_judging'] = $request->has('anonymous_judging');

        $event->update($validated);

        return redirect()->route('admin.event.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event) {
        $event->delete();

        return redirect()->route('admin.event.index')->with('success', 'Event deleted successfully.');
    }

    /**
     * List all participant submissions that need approval.
     */
    public function participants(Request $request) {
$status = $request->get('status', 'reviewed');
        
        $submissions = Submission::with(['participant', 'event'])
            ->when($status !== 'all', function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.participants', compact('submissions', 'status'));
    }

    /**
     * Approve a participant submission.
     */
    public function approveParticipant(Submission $submission) {
        $submission->update(['status' => 'reviewed']);
        
        return redirect()->route('admin.participants.index')
            ->with('success', 'Participant approved successfully.');
    }

    /**
     * Decline a participant submission.
     */
    public function declineParticipant(Submission $submission) {
        $submission->update(['status' => 'draft']);
        
        return redirect()->route('admin.participants.index')
            ->with('success', 'Participant declined successfully.');
    }

    /**
     * Show edit form for participant submission.
     */
    public function editParticipant(Submission $submission) {
        $submission->load(['participant', 'event']);
        return view('admin.participants.edit', compact('submission'));
    }

    /**
     * Update participant submission.
     */
    public function updateParticipant(Request $request, Submission $submission) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:pending,reviewed,draft',
        ]);

        $submission->update($validated);

        return redirect()->route('admin.participants.index')
            ->with('success', 'Submission updated successfully.');
    }

    /**
     * Delete participant submission.
     */
    public function destroyParticipant(Submission $submission) {
        $submission->delete();

        return redirect()->route('admin.participants.index')
            ->with('success', 'Submission deleted successfully.');
    }

    /**
     * Display participant submission details.
     */
    public function showParticipant(Submission $submission) {
        $submission->load(['participant', 'event']);
        return view('admin.participants.show', compact('submission'));
    }
}
