<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check if accessed via judge routes
        if (request()->routeIs('judge.criteria.index')) {
            // Verify user is authenticated
            if (!Auth::check()) {
                return redirect()->route('login')->with('error', 'Please login to access this page.');
            }
            
            // For judges, show ALL active criteria regardless of event assignment
            // This ensures criteria created by admin displays on judge side
            $criteria = Criteria::with('event', 'category')
                ->where('status', 'active') // Only show active criteria
                ->get();
            return view('judge.criteria.index', compact('criteria'));
        }

        // Default admin view - with status filter
        $query = Criteria::with(['event', 'category', 'scores']);
        
        // Filter by status if provided
        if (request()->has('status') && request('status') !== '') {
            $query->where('status', request('status'));
        }
        
        $criteria = $query->get();
        
        // Get score counts efficiently using the loaded relationship
        $scoreCounts = [];
        foreach ($criteria as $c) {
            $scoreCounts[$c->id] = $c->scores->count();
        }
        
        return view('admin.criteria.index', compact('criteria', 'scoreCounts'));
    }

    /**
     * Show the form for creating a score for a specific criterion.
     */
    public function createScore(Criteria $criteria)
    {
        // Verify user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }
        
        // Assignment check removed - allow scoring any active criteria
        \Illuminate\Support\Facades\Log::info('Judge scoring access - createScore', [
            'judge_id' => Auth::id(), 
            'criteria_id' => $criteria->id,
            'event_id' => $criteria->event_id,
            'assigned_event_ids' => Auth::user()->judgeAssignments()->pluck('event_id')->toArray()
        ]);
        
        // Verify criteria is active
        if ($criteria->status !== 'active') {
            return redirect()->route('judge.criteria.index')
                ->with('error', 'This criterion is not active.');
        }
        
        // Get participants for this criteria's event
        $eventId = $criteria->event_id;
        
        // Get participants who have submissions in this event
        $participants = User::where('role', 'participant')
            ->whereHas('submissions', function ($query) use ($eventId) {
                $query->where('event_id', $eventId)
                      ->where('status', 'reviewed');
            })
            ->get();

        return view('judge.scoring.create', compact('criteria', 'participants'));
    }

    /**
     * Store a newly created score in storage.
     */
    public function storeScore(Request $request, Criteria $criteria)
    {
        // Verify user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }
        
        // Assignment check removed - allow scoring any active criteria
        \Illuminate\Support\Facades\Log::info('Judge scoring access - storeScore', [
            'judge_id' => Auth::id(), 
            'criteria_id' => $criteria->id,
            'event_id' => $criteria->event_id,
            'assigned_event_ids' => Auth::user()->judgeAssignments()->pluck('event_id')->toArray()
        ]);
        
        // Verify criteria is active
        if ($criteria->status !== 'active') {
            return redirect()->route('judge.criteria.index')
                ->with('error', 'This criterion is not active.');
        }
        
        $request->validate([
            'participant_id' => 'required|exists:users,id',
            'score' => 'required|numeric|min:0|max:' . ($criteria->max_score ?? 100),
            'comments' => 'nullable|string|max:1000',
            'status' => 'required|in:draft,pending,submitted',
        ]);

        // Check if score already exists for this judge, participant, and criteria
        $existingScore = Score::where('judge_id', Auth::id())
            ->where('participant_id', $request->participant_id)
            ->where('criteria_id', $criteria->id)
            ->first();

        if ($existingScore) {
            // Update existing score
            $existingScore->update([
                'score' => $request->score,
                'comments' => $request->comments,
                'status' => $request->status,
            ]);
            $message = 'Score updated successfully.';
        } else {
            // Create new score
            Score::create([
                'judge_id' => Auth::id(),
                'participant_id' => $request->participant_id,
                'event_id' => $criteria->event_id,
                'criteria_id' => $criteria->id,
                'score' => $request->score,
                'comments' => $request->comments,
                'status' => $request->status,
            ]);
            $message = 'Score created successfully.';
        }

        return redirect()->route('judge.criteria.index')->with('success', $message);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $events = Event::all();
        return view('admin.criteria.create', compact('categories', 'events'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'event_id' => 'required|exists:events,id',
            'category_id' => 'required|exists:categories,id',
            'max_score' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'percentage_weight' => 'nullable|numeric|min:0|max:100',
            'status' => 'nullable|in:active,inactive',
        ]);

        $data = $request->all();
        // Set default status if not provided
        if (!isset($data['status'])) {
            $data['status'] = 'active';
        }

        Criteria::create($data);

        return redirect()->route('admin.criteria.index')->with('success', 'Criteria created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Criteria $criteria)
    {
        $criteria->load(['category', 'event']);
        return view('admin.criteria.show', compact('criteria'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Criteria $criteria)
    {
        if (!$criteria->exists) {
            return redirect()->route('admin.criteria.index')->with('error', 'Criterion not found.');
        }

        $categories = Category::all();
        $events = Event::all();
        return view('admin.criteria.edit', compact('criteria', 'categories', 'events'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Criteria $criteria)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'event_id' => 'required|exists:events,id',
            'category_id' => 'required|exists:categories,id',
            'max_score' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'percentage_weight' => 'nullable|numeric|min:0|max:100',
            'status' => 'nullable|in:active,inactive',
        ]);

        $criteria->update($request->all());

        return redirect()->route('admin.criteria.index')->with('success', 'Criterion updated successfully.');
    }

    /**
     * Remove the specified resource from storage (Soft Delete).
     */
    public function destroy(Criteria $criteria)
    {
        // Check if scores exist for this criteria
        $scoreCount = Score::where('criteria_id', $criteria->id)->count();
        
        if ($scoreCount > 0) {
            return redirect()->route('admin.criteria.index')
                ->with('error', 'Cannot delete criterion "' . $criteria->name . '" because it has ' . $scoreCount . ' score(s) associated with it. Please deactivate it instead.');
        }

        // Soft delete: set status to inactive instead of permanent deletion
        $criteria->update(['status' => 'inactive']);

        return redirect()->route('admin.criteria.index')->with('success', 'Criteria deactivated successfully.');
    }

    /**
     * Toggle the status of the specified criterion (activate/deactivate).
     */
    public function toggleStatus(Criteria $criteria)
    {
        $newStatus = $criteria->status === 'active' ? 'inactive' : 'active';
        $criteria->update(['status' => $newStatus]);

        $statusText = $newStatus === 'active' ? 'activated' : 'deactivated';
        return redirect()->route('admin.criteria.index')->with('success', 'Criteria ' . $statusText . ' successfully.');
    }
}
