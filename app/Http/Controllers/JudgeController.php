<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Event;
use App\Models\Submission;
use App\Models\Score;

class JudgeController extends Controller
{
    /**
     * Get the currently authenticated user.
     */
    private function getAuthUser(): ?User
    {
        return Auth::user();
    }

    public function dashboard() {
        /** @var User $user */
        $user = Auth::user();
        
        // Get assigned events count
        $assignedEvents = $user->judgeAssignments()->count();
        
        // Get assigned event IDs
        $assignedEventIds = $user->judgeAssignments()->pluck('event_id');
        
        // Get participants count from assigned events
        $participants = \App\Models\User::where('role', 'participant')
            ->whereHas('submissions', function ($query) use ($assignedEventIds) {
                $query->whereIn('event_id', $assignedEventIds);
            })->count();
        
        // Get pending scores count
        $pendingScores = \App\Models\Score::where('judge_id', Auth::id())
            ->where('status', 'pending')
            ->count();
        
        // Get submitted scores count
        $submittedScores = \App\Models\Score::where('judge_id', Auth::id())
            ->where('status', 'submitted')
            ->count();
        
        return view('judge.dashboard', compact(
            'assignedEvents',
            'participants',
            'pendingScores',
            'submittedScores'
        ));
    }

    public function participants(Request $request) {
        $eventId = $request->query('event_id');

        $query = User::where('role', 'participant')
            ->with('receivedScores');

        if ($eventId) {
            $query->whereHas('submissions', function ($q) use ($eventId) {
                $q->where('event_id', $eventId);
            });
        } else {
            // If no event_id, show participants from events assigned to this judge
            /** @var User $authUser */
            $authUser = Auth::user();
            $assignedEventIds = $authUser->judgeAssignments()->pluck('event_id');
            $query->whereHas('submissions', function ($q) use ($assignedEventIds) {
                $q->whereIn('event_id', $assignedEventIds);
            });
        }

        $participants = $query->paginate(15);

        return view('judge.participants', compact('participants', 'eventId'));
    }

    public function manageParticipants(Request $request) {
        $eventId = $request->query('event_id');

        $query = User::where('role', 'participant')
            ->with('receivedScores');

        if ($eventId) {
            $query->whereHas('submissions', function ($q) use ($eventId) {
                $q->where('event_id', $eventId);
            });
        } else {
            // If no event_id, show participants from events assigned to this judge
            /** @var User $authUser */
            $authUser = Auth::user();
            $assignedEventIds = $authUser->judgeAssignments()->pluck('event_id');
            $query->whereHas('submissions', function ($q) use ($assignedEventIds) {
                $q->whereIn('event_id', $assignedEventIds);
            });
        }

        $participants = $query->paginate(15);

        return view('judge.manage_participants.index', compact('participants', 'eventId'));
    }

public function reviewScores(Request $request) {
        $query = Score::with(['participant', 'event', 'criteria'])
            ->where('judge_id', Auth::id());

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $scores = $query->paginate(15);

        // Get available criteria for scoring (events assigned to this judge)
        $assignedEventIds = Auth::user()->judgeAssignments()->pluck('event_id');
        
        $availableCriteria = \App\Models\Criteria::with(['event', 'category'])
            ->whereIn('event_id', $assignedEventIds)
            ->where('status', 'active')
            ->get();

        return view('judge.review-scores', compact('scores', 'availableCriteria'));
    }

    public function scoringEdit($scoreId = null) {
        /** @var User $user */
        $user = Auth::user();
        
        if ($scoreId) {
            // Get specific score
            $score = Score::with(['judge', 'participant', 'event', 'criteria'])
                ->where('judge_id', $user->id)
                ->findOrFail($scoreId);
            
            return view('judge.scoring.edit', compact('score'));
        }
        
        // If no score ID, show all scores for the judge to choose from
        $scores = Score::with(['judge', 'participant', 'event', 'criteria'])
            ->where('judge_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->paginate(15);
        
        return view('judge.scoring.index', compact('scores'));
    }

    public function profile() {
        /** @var User $user */
        $user = Auth::user();
        return view('judge.profile', compact('user'));
    }

    public function scoringUpdate(Request $request, $scoreId) {
        $score = Score::where('judge_id', Auth::id())->findOrFail($scoreId);

        $validated = $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'comments' => 'nullable|string',
            'status' => 'required|in:draft,pending,submitted',
        ]);

        $score->update($validated);

        return redirect()->route('judge.review-scores')->with('success', 'Score updated successfully.');
    }

    /**
     * Get events assigned to the logged-in judge.
     */
 
    public function myEvents() {
        /** @var User $authUser */
        $authUser = Auth::user();
        
        if ($authUser && $authUser->role === 'judge') {
            $events = $authUser->judgeAssignments()
                ->with('event')
                ->get()
                ->pluck('event')
                ->filter();
            return view('judge.events.index', compact('events'));
        } else {
            $events = Event::paginate(15);
            return view('judge.events.index', compact('events'));
        }
    
    }

    /**
     * Get events assigned to the logged-in judge (alias for myEvents).
     */
    public function assignedEvents()
    {
        return $this->myEvents();
    }
}
