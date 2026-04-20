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
use Illuminate\Support\Facades\Log;

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
        Log::info('=== JUDGE MANAGE PARTICIPANTS DEBUG ===');
        Log::info('Judge ID: ' . Auth::id() . ', Role: ' . Auth::user()->role);
        
        /** @var User $authUser */
        $authUser = Auth::user();
        $assignedEventIds = $authUser->judgeAssignments()->pluck('event_id');
        
        Log::info('Assigned Event IDs: ' . $assignedEventIds->toJson());
        Log::info('Assigned Events Count: ' . $assignedEventIds->count());
        
        // DEBUG: Total submissions in assigned events (any status)
        $totalSubsAnyStatus = Submission::whereIn('event_id', $assignedEventIds)->count();
        Log::info('Total submissions in assigned events (any status): ' . $totalSubsAnyStatus);
        
        $query = Submission::with(['participant', 'event'])
            ->whereIn('event_id', $assignedEventIds);

        // Relaxed filter for debugging - show ALL submissions initially
        // $query->where('status', 'reviewed');  // Temporarily commented
        // $query->whereHas('participant', function ($q) {
        //     $q->where('status', 'active');
        // });

        // Search filter: participant name/email or submission title/description
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('participant', function ($participantQuery) use ($search) {
                    $participantQuery->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                })->orWhere('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Search filter: participant name/email or submission title/description
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('participant', function ($participantQuery) use ($search) {
                    $participantQuery->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                })->orWhere('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Event filter
        if ($eventId = $request->get('event_id')) {
            $query->where('event_id', $eventId);
        }
        
        // DEBUG LOGGING
        Log::info('Final Query SQL: ' . $query->toSql());
        Log::info('Final Query Bindings: ' . json_encode($query->getBindings()));
        Log::info('Final Query Count Before Paginate: ' . $query->count());
        
        $submissions = $query->paginate(15)->appends($request->only(['search', 'event_id']));
        
        Log::info('Final Results Count: ' . $submissions->total());
        Log::info('=== END DEBUG ===');

        // Get assigned events for filter dropdown
        $events = Event::whereIn('id', $assignedEventIds)->get();

        return view('judge.manage_participants.index', compact('submissions', 'events', 'eventId', 'search'));
    }

public function reviewScores(Request $request) {
        /** @var User $user */
        $user = Auth::user();
        $judgeId = $user->id;
        
        $assignedEventIds = $user->judgeAssignments()->pluck('event_id');
        
        // Base participant query: approved participants (reviewed submissions) from assigned events
        $query = User::where('role', 'participant')
            ->whereHas('submissions', function ($q) use ($assignedEventIds) {
                $q->whereIn('event_id', $assignedEventIds)->where('status', 'reviewed');
            })
            ->with(['submissions.event', 'receivedScores.criteria.category.event'])
            ->select('id', 'name', 'email');
        
        // Search filter
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        
        // Event filter
        if ($eventId = $request->get('event_id')) {
            $query->whereHas('submissions', function ($q) use ($eventId) {
                $q->where('event_id', $eventId)->where('status', 'reviewed');
            });
        }
        
        // Category filter (via criteria with submissions)
        if ($categoryId = $request->get('category_id')) {
            $query->whereHas('receivedScores.criteria.category', function ($q) use ($categoryId) {
                $q->where('id', $categoryId);
            });
        }
        
        $participants = $query->paginate(15)->appends($request->only(['search', 'event_id', 'category_id']));
        
        $participantIds = $participants->pluck('id');
        
        // Get ALL criteria from assigned events (for table columns)
        $allCriteria = Criteria::with(['category:id,name', 'event:id,event_name'])
            ->whereIn('event_id', $assignedEventIds)
            ->where('status', 'active')
            ->get();
        
        // Get judge's scores for these participants to compute scoreData
        $scores = Score::with(['criteria', 'criteria.category'])
            ->where('judge_id', $judgeId)
            ->whereIn('participant_id', $participantIds)
            ->get();
        
        // Group scores to compute scoreData like scoringParticipantsTable
        $scoresByParticipant = [];
        foreach ($scores as $score) {
            $pid = $score->participant_id;
            $cid = $score->criteria->category_id ?? 'uncategorized';
            
            if (!isset($scoresByParticipant[$pid])) {
                $scoresByParticipant[$pid] = [];
            }
            if (!isset($scoresByParticipant[$pid][$cid])) {
                $scoresByParticipant[$pid][$cid] = [
                    'event_id' => $score->event_id,
                    'event_name' => $score->criteria->event->event_name ?? 'N/A',
                    'category_id' => $cid,
                    'category_name' => $score->criteria->category->name ?? 'Uncategorized',
                    'criteria_scores' => [],
                    'total_score' => 0,
                    'max_possible' => 0,
                    'overall_status' => null,
                ];
            }
            
            $critId = $score->criteria_id;
            $scoresByParticipant[$pid][$cid]['criteria_scores'][$critId] = [
                'score' => $score->score,
                'status' => $score->status,
                'max_score' => $score->criteria->max_score ?? 100,
            ];
            
            $scoresByParticipant[$pid][$cid]['total_score'] += $score->score;
            $scoresByParticipant[$pid][$cid]['max_possible'] += ($score->criteria->max_score ?? 100);
            
            // Status priority: submitted > pending > draft
            $status = $scoresByParticipant[$pid][$cid]['overall_status'];
            if ($score->status === 'submitted' || $status === 'submitted') {
                $scoresByParticipant[$pid][$cid]['overall_status'] = 'submitted';
            } elseif ($score->status === 'pending' || $status === 'pending') {
                $scoresByParticipant[$pid][$cid]['overall_status'] = 'pending';
            } elseif ($score->status === 'draft' && !$status) {
                $scoresByParticipant[$pid][$cid]['overall_status'] = 'draft';
            }
        }
        
        // Attach to participants
        foreach ($participants as $participant) {
            $participant->scoreData = $scoresByParticipant[$participant->id] ?? [];
        }
        
// Stats - Enhanced with totals
        $stats = [
            'total_participants' => $query->count(),
            'total_scores' => Score::where('judge_id', $judgeId)->count(),
            'submitted_scores' => Score::where('judge_id', $judgeId)->where('status', 'submitted')->count(),
            'pending_scores' => Score::where('judge_id', $judgeId)->where('status', 'pending')->count(),
            'avg_score' => Score::where('judge_id', $judgeId)->avg('score') ?? 0,
        ];

        // Compute participant totals across ALL categories (grand totals)
        $participantTotalsQuery = Score::selectRaw('participant_id, SUM(score) as grand_total_score, COUNT(DISTINCT criteria_id) as total_criteria_scored')
            ->where('judge_id', $judgeId)
            ->groupBy('participant_id')
            ->orderByDesc('grand_total_score');

        // Apply same filters as scores query
        if ($search = $request->get('search')) {
            $participantTotalsQuery->whereHas('participant', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        if ($eventId = $request->get('event_id')) {
            $participantTotalsQuery->where('event_id', $eventId);
        }
        if ($categoryId = $request->get('category_id')) {
            $participantTotalsQuery->whereHas('criteria.category', function ($q) use ($categoryId) {
                $q->where('id', $categoryId);
            });
        }

        $participantTotals = $participantTotalsQuery->get()->map(function ($row) {
            $participant = User::select('id', 'name', 'email')->find($row->participant_id);
            // Get total possible score for percentage calculation
            $totalPossibleQuery = Score::selectRaw('SUM(criteria.max_score) as total_max')
                ->join('criteria', 'scores.criteria_id', '=', 'criteria.id')
                ->where('scores.judge_id', $row->judge_id ?? auth()->id())
                ->where('scores.participant_id', $row->participant_id)
                ->groupBy('scores.participant_id');
            
            if (request('event_id')) {
                $totalPossibleQuery->where('scores.event_id', request('event_id'));
            }
            if (request('category_id')) {
                $totalPossibleQuery->whereHas('criteria.category', function ($q) {
                    $q->where('id', request('category_id'));
                });
            }
            
            $totalPossible = $totalPossibleQuery->value('total_max') ?? 1;
            $percentage = $totalPossible > 0 ? round(($row->grand_total_score / $totalPossible) * 100, 1) : 0;
            
            return [
                'participant' => $participant,
                'grand_total_score' => $row->grand_total_score,
                'total_criteria_scored' => $row->total_criteria_scored,
                'percentage' => $percentage,
                'total_possible' => $totalPossible,
            ];
        });

        // Top 5 performers
        $topPerformers = $participantTotals->sortByDesc('grand_total_score')->take(5);
        
        $assignedEvents = Event::whereIn('id', $assignedEventIds)->get(['id', 'event_name']);
        $assignedCategories = Category::whereIn('event_id', $assignedEventIds)->get(['id', 'name']);
        
        // Get paginated scores for this judge (matching view expectations)
        $scoreQuery = Score::with(['participant', 'event', 'criteria', 'criteria.category'])
            ->where('judge_id', $judgeId);
        
        // Apply same filters as before (search -> participant name)
        if ($search = $request->get('search')) {
            $scoreQuery->whereHas('participant', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        
        if ($eventId = $request->get('event_id')) {
            $scoreQuery->where('event_id', $eventId);
        }
        
        if ($categoryId = $request->get('category_id')) {
            $scoreQuery->whereHas('criteria.category', function ($q) use ($categoryId) {
                $q->where('id', $categoryId);
            });
        }
        
        $scores = $scoreQuery->orderBy('updated_at', 'desc')->paginate(15)->appends($request->only(['search', 'event_id', 'category_id']));
        
        $assignedEvents = Event::whereIn('id', $assignedEventIds)->get(['id', 'event_name']);
        $assignedCategories = Category::whereIn('event_id', $assignedEventIds)->get(['id', 'name']);
        $categories = $assignedCategories; // for filter dropdown
        
        return view('judge.scoring.index', compact(
            'scores', 'categories', 'assignedEvents', 'assignedCategories', 
            'stats', 'participantTotals', 'topPerformers'
        ));
        
        return view('judge.scoring.index', compact('scores', 'categories', 'assignedEvents', 'assignedCategories'));
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

        // Update the score first
        $score->update($validated);

        // Validate the score using the ScoringValidationService
        $validationErrors = \App\Services\ScoringValidationService::validateScore($score);

        if (!empty($validationErrors)) {
            // Create validation records for errors
            foreach ($validationErrors as $error) {
                \App\Models\ScoreValidation::create([
                    'score_id' => $score->id,
                    'validation_type' => 'range',
                    'is_valid' => false,
                    'message' => $error,
                ]);
            }

            return redirect()->back()->with('error', 'Score validation failed: ' . implode(', ', $validationErrors));
        }

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
        
        Log::info('=== JUDGE SCORING CATEGORY DEBUG ===', [
            'judge_id' => $user->id,
            'method' => __METHOD__
        ]);
        
        // Get assigned event IDs
        $assignedEventIds = $user->judgeAssignments()->pluck('event_id');
        Log::info('Assigned Event IDs', ['count' => $assignedEventIds->count(), 'ids' => $assignedEventIds]);
        
        // Get categories from assigned events
        $categories = \App\Models\Category::whereIn('event_id', $assignedEventIds)
            ->where('status', 'active')
            ->with('event')
            ->get();
        Log::info('Loaded Categories', ['count' => $categories->count()]);
        
        $selectedCategoryId = $request->query('category_id');
        $selectedCategory = null;
        $participants = collect();
        $criteria = collect();
        
        if ($selectedCategoryId) {
            $selectedCategory = \App\Models\Category::with('event')->find($selectedCategoryId);
            Log::info('Selected Category', ['id' => $selectedCategoryId, 'found' => $selectedCategory ? 'yes' : 'no']);
            
            if ($selectedCategory) {
                // Get criteria for this category
                $criteriaIds = \App\Models\Criteria::where('category_id', $selectedCategoryId)
                    ->where('status', 'active')
                    ->pluck('id');
                $criteria = \App\Models\Criteria::with(['category', 'event'])
                    ->whereIn('id', $criteriaIds)
                    ->get();
                Log::info('Category Criteria', ['count' => $criteria->count(), 'ids' => $criteriaIds]);
                
                // Get participants who have submissions in the event associated with this category
                $eventId = $selectedCategory->event_id;
                $participantsQuery = User::where('role', 'participant')
                    ->whereHas('submissions', function ($query) use ($eventId) {
                        $query->where('event_id', $eventId)
                              ->whereIn('status', ['reviewed', 'approved']); // More flexible
                    })
                    ->with([
                        'participantProfile',
                        'receivedScores' => function ($query) use ($user, $criteriaIds) {
                            $query->where('judge_id', $user->id)
                                  ->whereIn('criteria_id', $criteriaIds);
                        },
                        'receivedScores.criteria'
                    ])
                    ->orderBy('name');
                
                $participants = $participantsQuery->get();
                Log::info('Loaded Participants', [
                    'count' => $participants->count(),
                    'event_id' => $eventId,
                    'query_sql' => $participantsQuery->toSql()
                ]);
                
                // Pre-calculate progress data for each participant
                foreach ($participants as $participant) {
                    $participantScores = $participant->receivedScores;
                    $completedCriteria = $participantScores->count();
                    $participant->scoring_progress = [
                        'completed' => $completedCriteria,
                        'total' => $criteriaIds->count(),
                        'percentage' => $criteriaIds->count() > 0 ? round(($completedCriteria / $criteriaIds->count()) * 100) : 0,
                        'total_score' => $participantScores->sum('score'),
                        'avg_score' => $participantScores->avg('score')
                    ];
                }
            }
        }

        // Stats calculations
        $judgeAssignmentsCount = $user->judgeAssignments()->count();
        $assignedEventsCount = $user->judgeAssignments()->distinct('event_id')->count('event_id');
        
        // Category-specific scoring progress (only when category selected)
        $scoringProgressPercent = 0;
        if ($selectedCategory && $participants->count() > 0 && isset($criteriaIds)) {
            $completedParticipants = $participants->filter(fn($p) => 
                $p->scoring_progress['completed'] >= $criteriaIds->count()
            )->count();
            $scoringProgressPercent = round(($completedParticipants / $participants->count()) * 100);
        }
        
        Log::info('Final Data Summary', [
            'categories_count' => $categories->count(),
            'participants_count' => $participants->count(),
            'criteria_count' => $criteria->count(),
            'scoring_progress_percent' => $scoringProgressPercent
        ]);
        
        return view('judge.scoring.category', compact(
            'categories',
            'selectedCategory',
            'participants',
            'criteria',
            'judgeAssignmentsCount',
            'assignedEventsCount',
            'scoringProgressPercent'
        )); // Added scoringProgressPercent
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
        $criteria = \App\Models\Criteria::with(['category', 'event'])
            ->where('category_id', $category->id)
            ->where('status', 'active')
            ->get();
        
        if ($criteria->isEmpty()) {
Log::warning('No active criteria found for category ID: ' . $category->id . ', Judge: ' . $user->id);
            return redirect()->route('judge.scoring.category')->with('warning', 'No active criteria available for this category.');
        }
        
        // Verify participant has submission in this event
        $hasSubmission = $participant->submissions()
            ->where('event_id', $category->event_id)
            ->where('status', 'reviewed')
            ->exists();
        
        // Verify judge is assigned to this participant for this event
        $isAssignedToParticipant = $user->judgeParticipantAssignments()
            ->where('event_id', $category->event_id)
            ->where('participant_id', $participant->id)
            ->exists();
            
        if (!$isAssignedToParticipant) {
            return redirect()->route('judge.scoring.category')
                ->with('error', 'You are not assigned to score this participant in this event. Contact admin for assignment.');
        }
        
        if (!$hasSubmission) {
            return redirect()->route('judge.scoring.category')
                ->with('error', 'This participant does not have a reviewed submission in this event.');
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
        
        // Verify judge is assigned to this participant
        $isAssignedToParticipant = $user->judgeParticipantAssignments()
            ->where('event_id', $category->event_id)
            ->where('participant_id', $participant->id)
            ->exists();
            
        if (!$isAssignedToParticipant) {
            return redirect()->route('judge.scoring.category')
                ->with('error', 'You are not assigned to score this participant. Contact admin.');
        }
        
        // Get criteria for this category
        $criteria = \App\Models\Criteria::with(['category', 'event'])
            ->where('category_id', $category->id)
            ->where('status', 'active')
            ->get();
        
        if ($criteria->isEmpty()) {
Log::warning('No active criteria for category save, category: ' . $category->id);
            return redirect()->back()->with('error', 'No active criteria available.');
        }
        
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
                $score = $existingScore;
            } else {
                $score = Score::create([
                    'judge_id' => $user->id,
                    'participant_id' => $participant->id,
                    'event_id' => $category->event_id,
                    'criteria_id' => $criterion->id,
                    'score' => $scoreValue,
                    'comments' => $commentValue,
                    'status' => $status,
                ]);
            }

            // Validate the score using the ScoringValidationService
            $validationErrors = \App\Services\ScoringValidationService::validateScore($score);
            
            if (!empty($validationErrors)) {
                // Create validation records for errors
                foreach ($validationErrors as $error) {
                    \App\Models\ScoreValidation::create([
                        'score_id' => $score->id,
                        'validation_type' => 'range',
                        'is_valid' => false,
                        'message' => $error,
                    ]);
                }
            }
        }
        
        return redirect()->route('judge.scoring.category', ['category_id' => $category->id])
            ->with('success', 'Scores saved successfully for ' . $participant->name . ' in ' . $category->name . '.');
    }

    /**
     * Display bulk scoring form for all participants in a category.
     */
    public function bulkScoreCategory(\App\Models\Category $category)
    {
        $user = Auth::user();
        
        // Verify judge is assigned to the event
        if (!$user->judgeAssignments()->where('event_id', $category->event_id)->exists()) {
            return redirect()->route('judge.review-scores')
                ->with('error', 'You are not assigned to this event.');
        }
        
        // Note: Bulk scoring shows all participants for event (admin handles assignments)
        // Individual scoring checks will enforce participant assignments
        
        if ($category->status !== 'active') {
            return redirect()->route('judge.review-scores')
                ->with('error', 'Category is not active.');
        }
        
        // Get active criteria for this category
        $criteria = Criteria::with(['category', 'event'])
            ->where('category_id', $category->id)
            ->where('status', 'active')
            ->get();
        
        if ($criteria->isEmpty()) {
Log::warning('No criteria for bulk scoring category: ' . $category->id);
            return redirect()->route('judge.review-scores')
                ->with('error', 'No active criteria found for this category.');
        }
        
        // Get participants with reviewed submissions for this event
        $participants = User::where('role', 'participant')
            ->whereHas('submissions', function($q) use ($category) {
                $q->where('event_id', $category->event_id)->where('status', 'reviewed');
            })
            ->with(['receivedScores' => function($q) use ($category) {
                $q->whereIn('criteria_id', $category->pluck('id'));
            }])
            ->orderBy('name')
            ->get(['id', 'name']);
        
        // Get existing scores grouped by participant and criteria
        $existingScores = Score::where('judge_id', $user->id)
            ->whereIn('criteria_id', $criteria->pluck('id'))
            ->whereIn('participant_id', $participants->pluck('id'))
            ->get()
            ->keyBy(function($score) {
                return $score->participant_id . '_' . $score->criteria_id;
            });
        
        return view('judge.scoring.bulk-category', compact('category', 'criteria', 'participants', 'existingScores'));
    }

    /**
     * Store bulk scores for all participants in a category.
     */
    public function storeBulkScores(Request $request, \App\Models\Category $category)
    {
        $user = Auth::user();
        
        if (!$user->judgeAssignments()->where('event_id', $category->event_id)->exists()) {
            return redirect()->route('judge.review-scores')->with('error', 'Access denied.');
        }
        
        $criteria = Criteria::where('category_id', $category->id)
            ->where('status', 'active')
            ->pluck('id', 'id');
        
        $participants = User::where('role', 'participant')
            ->whereHas('submissions', function($q) use ($category) {
                $q->where('event_id', $category->event_id)->where('status', 'reviewed');
            })
            ->pluck('id');
        
        $overallStatus = $request->input('overall_status', 'draft');
        $updatedCount = 0;
        $createdCount = 0;
        
        foreach ($participants as $participantId) {
            foreach ($criteria as $criteriaId) {
                $scoreKey = "p{$participantId}_c{$criteriaId}_score";
                $commentKey = "p{$participantId}_c{$criteriaId}_comment";
                
                if ($request->filled($scoreKey)) {
                    $scoreData = [
                        'score' => $request->{$scoreKey},
                        'comments' => $request->{$commentKey} ?? '',
                        'status' => $overallStatus,
                    ];
                    
                    $score = Score::updateOrCreate(
                        [
                            'judge_id' => $user->id,
                            'participant_id' => $participantId,
                            'criteria_id' => $criteriaId,
                            'event_id' => $category->event_id,
                        ],
                        $scoreData
                    );
                    
                    // Validate the score using the ScoringValidationService
                    $validationErrors = \App\Services\ScoringValidationService::validateScore($score);
                    
                    if (!empty($validationErrors)) {
                        // Create validation records for errors
                        foreach ($validationErrors as $error) {
                            \App\Models\ScoreValidation::create([
                                'score_id' => $score->id,
                                'validation_type' => 'range',
                                'is_valid' => false,
                                'message' => $error,
                            ]);
                        }
                    }
                    
                    if ($score->wasRecentlyCreated) {
                        $createdCount++;
                    } else {
                        $updatedCount++;
                    }
                }
            }
        }
        
        $message = "Bulk scoring completed: {$createdCount} new scores created, {$updatedCount} scores updated.";
        
        return redirect()->route('judge.review-scores')
            ->with('success', $message);
    }

    /**
     * Delete a score record.
     */
    public function destroyScore(Score $score)
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Only allow judge to delete their own scores
        if ($score->judge_id !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized to delete this score.');
        }
        
        $score->delete();
        
        return redirect()->back()->with('success', 'Score deleted successfully.');
    }

    /**
     * Judge scoring index page.
     * Main entry point for judge scoring: shows overview and links to detailed scoring.
     */
    public function index(Request $request)
    {
        // Delegate to reviewScores for full functionality
        return $this->reviewScores($request);
    }

    /**
     * Show participants scoring table with all their scores.
     * Displays participants in a table format with scores aggregated by category/criteria.
     */
    public function scoringParticipantsTable(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $judgeId = $user->id;
        
        $assignedEventIds = $user->judgeAssignments()->pluck('event_id');
        
        // Base participant query: approved participants (reviewed submissions) from assigned events
        $query = User::where('role', 'participant')
            ->whereHas('submissions', function ($q) use ($assignedEventIds) {
                $q->whereIn('event_id', $assignedEventIds)->where('status', 'reviewed');
            })
            ->with(['submissions.event', 'receivedScores.criteria.category.event'])
            ->select('id', 'name', 'email');
        
        // Search filter
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        
        // Event filter
        if ($eventId = $request->get('event_id')) {
            $query->whereHas('submissions', function ($q) use ($eventId) {
                $q->where('event_id', $eventId)->where('status', 'reviewed');
            });
        }
        
        // Category filter (via criteria with scores)
        if ($categoryId = $request->get('category_id')) {
            $query->whereHas('receivedScores.criteria.category', function ($q) use ($categoryId) {
                $q->where('id', $categoryId);
            });
        }
        
        // Status filter
        if ($status = $request->get('status')) {
            $query->whereHas('receivedScores', function ($q) use ($status) {
                $q->where('status', $status);
            });
        }
        
        $participants = $query->paginate(15)->appends($request->only(['search', 'event_id', 'category_id', 'status']));
        
        $participantIds = $participants->pluck('id');
        
        // Get ALL criteria from assigned events (for table columns)
        $allCriteria = Criteria::with(['category:id,name', 'event:id,event_name'])
            ->whereIn('event_id', $assignedEventIds)
            ->where('status', 'active')
            ->get();
        
        // Get judge's scores for these participants to compute scoreData
        $scores = Score::with(['criteria', 'criteria.category'])
            ->where('judge_id', $judgeId)
            ->whereIn('participant_id', $participantIds)
            ->get();
        
        // Group scores to compute scoreData like view expects
        $scoresByParticipant = [];
        foreach ($scores as $score) {
            $pid = $score->participant_id;
            $cid = $score->criteria->category_id ?? 'uncategorized';
            
            if (!isset($scoresByParticipant[$pid])) {
                $scoresByParticipant[$pid] = [];
            }
            if (!isset($scoresByParticipant[$pid][$cid])) {
                $scoresByParticipant[$pid][$cid] = [
                    'event_id' => $score->event_id,
                    'event_name' => $score->criteria->event->event_name ?? 'N/A',
                    'category_id' => $cid,
                    'category_name' => $score->criteria->category->name ?? 'Uncategorized',
                    'criteria_scores' => [],
                    'total_score' => 0,
                    'max_possible' => 0,
                    'overall_status' => null,
                ];
            }
            
            $critId = $score->criteria_id;
            $scoresByParticipant[$pid][$cid]['criteria_scores'][$critId] = [
                'score' => $score->score,
                'status' => $score->status,
                'max_score' => $score->criteria->max_score ?? 100,
            ];
            
            $scoresByParticipant[$pid][$cid]['total_score'] += $score->score;
            $scoresByParticipant[$pid][$cid]['max_possible'] += ($score->criteria->max_score ?? 100);
            
            // Status priority: submitted > pending > draft
            $statusPriority = ['submitted' => 3, 'pending' => 2, 'draft' => 1];
            $currentPriority = isset($statusPriority[$scoresByParticipant[$pid][$cid]['overall_status']]) ? $statusPriority[$scoresByParticipant[$pid][$cid]['overall_status']] : 0;
            $newPriority = $statusPriority[$score->status] ?? 0;
            if ($newPriority > $currentPriority) {
                $scoresByParticipant[$pid][$cid]['overall_status'] = $score->status;
            }
        }
        
        // Attach to participants
        foreach ($participants as $participant) {
            $participant->scoreData = $scoresByParticipant[$participant->id] ?? [];
        }
        
        // For filters
        $events = Event::whereIn('id', $assignedEventIds)->get(['id', 'event_name']);
        $categories = Category::whereIn('event_id', $assignedEventIds)->get(['id', 'name']);
        
        return view('judge.scoring.participants-table', compact('participants', 'allCriteria', 'categories', 'events'));
    }

}
