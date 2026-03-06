<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Event;
use App\Models\Category;
use App\Models\Criteria;
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
            ->with(['receivedScores', 'submissions']);

        if ($eventId) {
            $query->whereHas('submissions', function ($q) use ($eventId) {
                $q->where('event_id', $eventId)->where('status', 'reviewed');
            });
        } else {
            // If no event_id, show participants from events assigned to this judge
            // Only show participants with APPROVED submissions (status = 'reviewed')
            /** @var User $authUser */
            $authUser = Auth::user();
            $assignedEventIds = $authUser->judgeAssignments()->pluck('event_id');
            $query->whereHas('submissions', function ($q) use ($assignedEventIds) {
                $q->whereIn('event_id', $assignedEventIds)->where('status', 'reviewed');
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
        
        // Get assigned events for diagnostic purposes
        $assignedEvents = Event::whereIn('id', $assignedEventIds)->get();
        
        // Get categories from assigned events
        $assignedCategories = Category::whereIn('event_id', $assignedEventIds)->get();
        
        $availableCriteria = Criteria::with(['event', 'category'])
            ->whereIn('event_id', $assignedEventIds)
            ->where('status', 'active')
            ->get();

        return view('judge.review-scores', compact('scores', 'availableCriteria', 'assignedEvents', 'assignedCategories'));
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
        
        // Get diagnostic information for the view
        $assignedEventIds = $user->judgeAssignments()->pluck('event_id');
        $assignedEvents = Event::whereIn('id', $assignedEventIds)->get();
        $assignedCategories = Category::whereIn('event_id', $assignedEventIds)->get();
        
        // If no score ID, show all scores for the judge to choose from
        $scores = Score::with(['judge', 'participant', 'event', 'criteria'])
            ->where('judge_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->paginate(15);
        
        return view('judge.scoring.index', compact('scores', 'assignedEvents', 'assignedCategories'));
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

    /**
     * Show scoring interface by category.
     * Allows judges to score participants organized by their performance category.
     */
    public function scoringByCategory(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Get assigned event IDs
        $assignedEventIds = $user->judgeAssignments()->pluck('event_id');
        
        // Get categories from assigned events
        $categories = \App\Models\Category::whereIn('event_id', $assignedEventIds)
            ->where('status', 'active')
            ->with('event')
            ->get();
        
        $selectedCategoryId = $request->query('category_id');
        $selectedCategory = null;
        $participants = collect();
        $criteria = collect();
        
        if ($selectedCategoryId) {
            $selectedCategory = \App\Models\Category::with('event')->find($selectedCategoryId);
            
            if ($selectedCategory) {
                // Get criteria for this category
                $criteria = \App\Models\Criteria::where('category_id', $selectedCategoryId)
                    ->where('status', 'active')
                    ->get();
                
                // Get participants who have submissions in the event associated with this category
                $eventId = $selectedCategory->event_id;
                $participants = User::where('role', 'participant')
                    ->whereHas('submissions', function ($query) use ($eventId) {
                        $query->where('event_id', $eventId)->where('status', 'approved');
                    })
                    ->with(['receivedScores' => function ($query) use ($selectedCategoryId) {
                        $query->whereHas('criteria', function ($q) use ($selectedCategoryId) {
                            $q->where('category_id', $selectedCategoryId);
                        });
                    }])
                    ->get();
            }
        }
        
        return view('judge.scoring.category', compact(
            'categories',
            'selectedCategory',
            'participants',
            'criteria'
        ));
    }

/**
     * Get scores grouped by category for the judge.
     */
    public function scoresByCategory(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Get assigned event IDs
        $assignedEventIds = $user->judgeAssignments()->pluck('event_id');
        
        // Get assigned events for diagnostic purposes
        $assignedEvents = Event::whereIn('id', $assignedEventIds)->get();
        
        // Get categories from assigned events
        $assignedCategories = Category::whereIn('event_id', $assignedEventIds)
            ->where('status', 'active')
            ->with('event')
            ->get();
        
        // Get all scores for this judge with category relationship
        $query = Score::with(['participant', 'event', 'criteria', 'criteria.category'])
            ->where('judge_id', $user->id);
        
        // Filter by category if selected
        if ($request->has('category_id') && $request->category_id) {
            $query->whereHas('criteria', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }
        
        // Filter by status if selected
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        $scores = $query->orderBy('updated_at', 'desc')->paginate(15);
        
        // Use 'categories' for the view (as expected by the filter form)
        $categories = $assignedCategories;
        
        return view('judge.scoring.index', compact('scores', 'categories', 'assignedEvents', 'assignedCategories'));
    }

    /**
     * Show scoring form for a specific participant in a category.
     */
    public function scoreParticipantByCategory(\App\Models\Category $category, User $participant)
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Verify judge is assigned to the event
        $isAssignedToEvent = $user->judgeAssignments()
            ->where('event_id', $category->event_id)
            ->exists();
            
        if (!$isAssignedToEvent) {
            return redirect()->route('judge.scoring.category')
                ->with('error', 'You are not assigned to the event for this category.');
        }
        
        // Verify category is active
        if ($category->status !== 'active') {
            return redirect()->route('judge.scoring.category')
                ->with('error', 'This category is not active.');
        }
        
        // Get criteria for this category
        $criteria = \App\Models\Criteria::where('category_id', $category->id)
            ->where('status', 'active')
            ->get();
        
        // Verify participant has submission in this event
        $hasSubmission = $participant->submissions()
            ->where('event_id', $category->event_id)
            ->where('status', 'approved')
            ->exists();
        
        if (!$hasSubmission) {
            return redirect()->route('judge.scoring.category')
                ->with('error', 'This participant does not have a submission in this event.');
        }
        
        // Get existing scores for this participant in this category
        $existingScores = Score::where('judge_id', $user->id)
            ->where('participant_id', $participant->id)
            ->whereIn('criteria_id', $criteria->pluck('id'))
            ->get()
            ->keyBy('criteria_id');
        
        return view('judge.scoring.score-participant', compact('category', 'participant', 'criteria', 'existingScores'));
    }

    /**
     * Store scores for a participant in a category.
     */
    public function storeScoreByCategory(Request $request, \App\Models\Category $category, User $participant)
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Verify judge is assigned to the event
        $isAssignedToEvent = $user->judgeAssignments()
            ->where('event_id', $category->event_id)
            ->exists();
            
        if (!$isAssignedToEvent) {
            return redirect()->route('judge.scoring.category')
                ->with('error', 'You are not assigned to the event for this category.');
        }
        
        // Get criteria for this category
        $criteria = \App\Models\Criteria::where('category_id', $category->id)
            ->where('status', 'active')
            ->get();
        
        // Validate scores for each criterion
        foreach ($criteria as $criterion) {
            $scoreKey = 'score_' . $criterion->id;
            $commentKey = 'comment_' . $criterion->id;
            
            $request->validate([
                $scoreKey => 'required|numeric|min:0|max:' . ($criterion->max_score ?? 100),
                $commentKey => 'nullable|string|max:1000',
            ]);
        }
        
        $status = $request->input('status', 'draft');
        
        // Save scores for each criterion
        foreach ($criteria as $criterion) {
            $scoreKey = 'score_' . $criterion->id;
            $commentKey = 'comment_' . $criterion->id;
            
            $scoreValue = $request->input($scoreKey);
            $commentValue = $request->input($commentKey);
            
            // Check if score already exists
            $existingScore = Score::where('judge_id', $user->id)
                ->where('participant_id', $participant->id)
                ->where('criteria_id', $criterion->id)
                ->first();
            
            if ($existingScore) {
                $existingScore->update([
                    'score' => $scoreValue,
                    'comments' => $commentValue,
                    'status' => $status,
                ]);
            } else {
                Score::create([
                    'judge_id' => $user->id,
                    'participant_id' => $participant->id,
                    'event_id' => $category->event_id,
                    'criteria_id' => $criterion->id,
                    'score' => $scoreValue,
                    'comments' => $commentValue,
                    'status' => $status,
                ]);
            }
        }
        
        return redirect()->route('judge.scoring.category', ['category_id' => $category->id])
            ->with('success', 'Scores saved successfully for ' . $participant->name . ' in ' . $category->name . '.');
    }

    /**
     * Show participants scoring table with all their scores.
     * Displays participants in a table format with scores aggregated by category/criteria.
     */
    public function scoringParticipantsTable(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Get assigned event IDs
        $assignedEventIds = $user->judgeAssignments()->pluck('event_id');
        
        // Get categories from assigned events
        $categories = \App\Models\Category::whereIn('event_id', $assignedEventIds)
            ->where('status', 'active')
            ->with('event')
            ->get();
        
        // Get events for filter
        $events = \App\Models\Event::whereIn('id', $assignedEventIds)
            ->where('status', 'active')
            ->get();
        
        // Get all criteria from assigned events
        $allCriteria = \App\Models\Criteria::whereIn('event_id', $assignedEventIds)
            ->where('status', 'active')
            ->with('category')
            ->get();
        
        // Filter by category if selected
        $categoryId = $request->query('category_id');
        $eventId = $request->query('event_id');
        $statusFilter = $request->query('status');
        
        // Build base query for participants with scores
        $participantQuery = User::where('role', 'participant')
            ->whereHas('receivedScores', function ($query) use ($user, $assignedEventIds) {
                $query->where('judge_id', $user->id)
                    ->whereIn('event_id', $assignedEventIds);
            });
        
        // Get participants
        $participants = $participantQuery->paginate(15);
        
        // Get participant IDs for fetching scores
        $participantIds = $participants->pluck('id');
        
        // Get all scores for these participants by this judge
        $scoresQuery = Score::with(['criteria', 'criteria.category', 'event'])
            ->where('judge_id', $user->id)
            ->whereIn('participant_id', $participantIds);
        
        if ($categoryId) {
            $scoresQuery->whereHas('criteria', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }
        
        if ($eventId) {
            $scoresQuery->where('event_id', $eventId);
        }
        
        $allScores = $scoresQuery->get();
        
        // Group scores by participant and category
        $scoresByParticipant = [];
        foreach ($allScores as $score) {
            $pid = $score->participant_id;
            $categoryId = $score->criteria?->category?->id ?? 'uncategorized';
            
            if (!isset($scoresByParticipant[$pid])) {
                $scoresByParticipant[$pid] = [];
            }
            
            if (!isset($scoresByParticipant[$pid][$categoryId])) {
                $scoresByParticipant[$pid][$categoryId] = [
                    'event_id' => $score->event_id,
                    'event_name' => $score->event?->name ?? 'N/A',
                    'category_id' => $categoryId,
                    'category_name' => $score->criteria?->category?->name ?? 'Uncategorized',
                    'criteria_scores' => [],
                    'total_score' => 0,
                    'max_possible' => 0,
                    'overall_status' => null,
                ];
            }
            
            $criterionId = $score->criteria_id;
            $scoresByParticipant[$pid][$categoryId]['criteria_scores'][$criterionId] = [
                'score' => $score->score,
                'status' => $score->status,
                'max_score' => $score->criteria?->max_score ?? 100,
            ];
            
            $scoresByParticipant[$pid][$categoryId]['total_score'] += $score->score;
            $scoresByParticipant[$pid][$categoryId]['max_possible'] += ($score->criteria?->max_score ?? 100);
            
            // Determine overall status (prioritize submitted > pending > draft)
            $currentStatus = $scoresByParticipant[$pid][$categoryId]['overall_status'];
            if ($score->status === 'submitted') {
                $scoresByParticipant[$pid][$categoryId]['overall_status'] = 'submitted';
            } elseif ($score->status === 'pending' && $currentStatus !== 'submitted') {
                $scoresByParticipant[$pid][$categoryId]['overall_status'] = 'pending';
            } elseif ($score->status === 'draft' && $currentStatus === null) {
                $scoresByParticipant[$pid][$categoryId]['overall_status'] = 'draft';
            }
        }
        
        // Attach score data to participants
        foreach ($participants as $participant) {
            $pid = $participant->id;
            $participant->scoreData = $scoresByParticipant[$pid] ?? [];
        }
        
        return view('judge.scoring.participants-table', compact(
            'participants',
            'categories',
            'events',
            'allCriteria'
        ));
    }
}
