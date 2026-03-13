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
        /** @var User $authUser */
        $authUser = Auth::user();
        $assignedEventIds = $authUser->judgeAssignments()->pluck('event_id');

        $query = User::where('role', 'participant')
            ->with(['receivedScores', 'submissions.event'])
            ->withCount(['submissions as approved_submissions_count' => function ($q) {
                $q->where('status', 'reviewed');
            }, 'submissions as pending_submissions_count' => function ($q) {
                $q->whereIn('status', ['pending', 'draft', 'submitted', 'under_review']);
            }]);

        // Base filter: only approved participants from assigned events
        $query->whereHas('submissions', function ($q) use ($assignedEventIds) {
            $q->whereIn('event_id', $assignedEventIds)->where('status', 'reviewed');
        });

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

        $participants = $query->paginate(15)->appends($request->only(['search', 'event_id']));

        // Get assigned events for filter dropdown
        $events = Event::whereIn('id', $assignedEventIds)->get();

        return view('judge.manage_participants.index', compact('participants', 'events', 'eventId', 'search'));
    }

public function reviewScores(Request $request) {
        /** @var User $user */
        $user = Auth::user();
        $judgeId = $user->id;
        
        // Base query with optimized eager loading
        $query = Score::with([
                'participant:id,name',
                'event:id,event_name', 
                'criteria:id,name,max_score,category_id',
                'criteria.category:id,name'
            ])
            ->where('judge_id', $judgeId);
        
        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }
        if ($request->filled('category_id')) {
            $query->whereHas('criteria.category', function($q) use ($request) {
                $q->where('id', $request->category_id);
            });
        }
        
        $scores = $query->paginate(15)->appends($request->only(['status', 'event_id', 'category_id']));
        
        // Stats
        $stats = [
            'total' => Score::where('judge_id', $judgeId)->count(),
            'submitted' => Score::where('judge_id', $judgeId)->where('status', 'submitted')->count(),
            'draft' => Score::where('judge_id', $judgeId)->where('status', 'draft')->count(),
        ];
        
        // Assigned data
        $assignedEventIds = $user->judgeAssignments()->pluck('event_id');
        $assignedEvents = Event::whereIn('id', $assignedEventIds)->get(['id', 'event_name']);
        $assignedCategories = Category::whereIn('event_id', $assignedEventIds)->get(['id', 'name']);
        
        $availableCriteria = Criteria::with(['event:id,event_name', 'category:id,name'])
            ->whereIn('event_id', $assignedEventIds)
            ->where('status', 'active')
            ->get();
        
        if ($availableCriteria->isEmpty()) {
Log::warning('No available criteria for reviewScores', ['judge_id' => $user->id, 'event_ids' => $assignedEventIds]);
        }
        
        return view('judge.review-scores', compact('scores', 'availableCriteria', 'assignedEvents', 'assignedCategories', 'stats'));
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
                $criteria = \App\Models\Criteria::with(['category', 'event'])
                    ->where('category_id', $selectedCategoryId)
                    ->where('status', 'active')
                    ->get();
                
                if ($criteria->isEmpty()) {
Log::warning('No active criteria found for category ID: ' . $selectedCategoryId, ['judge_id' => $user->id]);
                }
                
                // Get participants who have submissions in the event associated with this category
                $eventId = $selectedCategory->event_id;
                $participants = User::where('role', 'participant')
                    ->whereHas('submissions', function ($query) use ($eventId) {
$query->where('event_id', $eventId)->where('status', 'reviewed');
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
        $allCriteria = \App\Models\Criteria::with(['category', 'event'])
            ->whereIn('event_id', $assignedEventIds)
            ->where('status', 'active')
            ->get();
        
        if ($allCriteria->isEmpty()) {
Log::warning('No criteria in scoringParticipantsTable', ['event_ids' => $assignedEventIds]);
        }
        
        // Filter by category if selected
        $categoryId = $request->query('category_id');
        $eventId = $request->query('event_id');
        $statusFilter = $request->query('status');
        
        // Build base query for participants with scores
        $participantQuery = User::where('role', 'participant')
            ->whereHas('submissions', function ($query) use ($assignedEventIds) {
                $query->whereIn('event_id', $assignedEventIds)->where('status', 'reviewed');
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

        /** @var \Illuminate\Support\Collection $allCriteria */
        /** @var \App\Models\Score $score */
        /** @var \App\Models\Criteria $currentCriterion */

        // Group scores by participant and category
        $scoresByParticipant = [];
        foreach ($allScores as $score) {
            /** @var \App\Models\Criteria $currentCriterion */
            $currentCriterion = $score->criteria;
            $pid = $score->participant_id;
            $categoryId = $currentCriterion?->category?->id ?? 'uncategorized';
            
            if (!isset($scoresByParticipant[$pid])) {
                $scoresByParticipant[$pid] = [];
            }
            
            if (!isset($scoresByParticipant[$pid][$categoryId])) {
                $scoresByParticipant[$pid][$categoryId] = [
                    'event_id' => $score->event_id,
                    'event_name' => $score->event?->name ?? 'N/A',
                    'category_id' => $categoryId,
            'category_name' => $currentCriterion?->category?->name ?? 'Uncategorized',
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
                'max_score' => $currentCriterion?->max_score ?? 100,
            ];

            $scoresByParticipant[$pid][$categoryId]['total_score'] += $score->score;
            $scoresByParticipant[$pid][$categoryId]['max_possible'] += ($currentCriterion?->max_score ?? 100);
            
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
