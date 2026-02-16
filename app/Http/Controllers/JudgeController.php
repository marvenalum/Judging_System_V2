<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;
use App\Models\Submission;
use App\Models\Score;

class JudgeController extends Controller
{
    public function dashboard() {
        $user = auth()->user();
        
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
        $pendingScores = \App\Models\Score::where('judge_id', $user->id)
            ->where('status', 'pending')
            ->count();
        
        // Get submitted scores count
        $submittedScores = \App\Models\Score::where('judge_id', $user->id)
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
            $assignedEventIds = auth()->user()->judgeAssignments()->pluck('event_id');
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
            $assignedEventIds = auth()->user()->judgeAssignments()->pluck('event_id');
            $query->whereHas('submissions', function ($q) use ($assignedEventIds) {
                $q->whereIn('event_id', $assignedEventIds);
            });
        }

        $participants = $query->paginate(15);

        return view('judge.manage_participants.index', compact('participants', 'eventId'));
    }

    public function reviewScores(Request $request) {
        $query = Score::with(['participant', 'event', 'criteria'])
            ->where('judge_id', auth()->id());

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $scores = $query->paginate(15);

        return view('judge.review-scores', compact('scores'));
    }

    public function scoringEdit($scoreId = null) {
        $user = auth()->user();
        
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
        $user = auth()->user();
        return view('judge.profile', compact('user'));
    }

    public function scoringUpdate(Request $request, $scoreId) {
        $score = Score::where('judge_id', auth()->id())->findOrFail($scoreId);

        $validated = $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'comments' => 'nullable|string',
            'status' => 'required|in:draft,pending,submitted',
        ]);

        $score->update($validated);

        return redirect()->route('judge.review-scores')->with('success', 'Score updated successfully.');
    }
}
