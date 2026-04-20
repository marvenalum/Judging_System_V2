<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use App\Models\Category;
use App\Models\Criteria;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $submissions = $user->submissions()->with('event')->get();
        $events = Event::where('event_status', '!=', 'completed')->get();
        
        return view('participant.dashboard', compact('submissions', 'events'));
    }

    // ==================== EVENTS ====================

    /**
     * Display a listing of events.
     */
    public function eventIndex()
    {
        $user = Auth::user();
        $events = Event::with(['submissions' => function($query) use ($user) {
            $query->where('participant_id', $user->id);
        }])->paginate(15);
        return view('participant.events.index', compact('events', 'user'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function eventCreate()
    {
        return view('participant.events.create');
    }

    /**
     * Store a newly created event.
     */
    public function eventStore(Request $request)
    {
        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'event_description' => 'required|string',
            'event_type' => 'required|in:pageant,contest,competition',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'event_status' => 'required|in:upcoming,ongoing,completed',
        ]);

        $validated['created_by'] = Auth::id();

        Event::create($validated);

        return redirect()->route('participant.event.index')->with('success', 'Event created successfully.');
    }

    /**
     * Display the specified event.
     */
    public function eventShow(Event $event)
    {
        $event->load('categories', 'user');
        return view('participant.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified event.
     */
    public function eventEdit(Event $event)
    {
        return view('participant.events.edit', compact('event'));
    }

    /**
     * Update the specified event.
     */
    public function eventUpdate(Request $request, Event $event)
    {
        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'event_description' => 'required|string',
            'event_type' => 'required|in:pageant,contest,competition',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'event_status' => 'required|in:upcoming,ongoing,completed',
        ]);

        $event->update($validated);

        return redirect()->route('participant.event.index')->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified event.
     */
    public function eventDestroy(Event $event)
    {
        $event->delete();

        return redirect()->route('participant.event.index')->with('success', 'Event deleted successfully.');
    }

    /**
     * Apply to an event (create a submission).
     */
    public function eventApply(Request $request, Event $event)
    {
        $user = Auth::user();

        // Check if profile is complete
        if (!$user->hasCompleteProfile()) {
            return redirect()->route('participant.event.index')->with('error', 'Please complete your participant profile first in Settings before applying.');
        }

        // Check if already applied
        if ($event->hasApplied($user->id)) {
            return redirect()->route('participant.event.index')->with('error', 'You have already applied to this event.');
        }

        // Create submission
        Submission::create([
            'participant_id' => $user->id,
            'event_id' => $event->id,
            'title' => $request->input('title', 'Application for ' . $event->event_name),
            'description' => $request->input('description', 'Participant application'),
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        return redirect()->route('participant.event.index')->with('success', 'You have successfully applied to the event!');
    }

    /**
     * Store participant profile.
     */
    public function participantProfileStore(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'age' => 'nullable|integer|min:13|max:100',
            'gender' => 'nullable|in:male,female,other',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'bio' => 'nullable|string',
            'height' => 'nullable|numeric|min:0|max:10',
            'weight' => 'nullable|numeric|min:0|max:1000',
            'measurements' => 'nullable|array',
            'photo' => 'nullable|string|max:255',
        ]);

        $profile = $user->participantProfile()->updateOrCreate(
            ['user_id' => $user->id],
            array_merge($validated, ['is_complete' => true])
        );

        return redirect()->route('participant.settings')->with('success', 'Participant profile updated successfully!');
    }

    // ==================== CATEGORIES ====================

    /**
     * Display a listing of categories.
     */
    public function categoryIndex()
    {
        $categories = Category::with('event')->paginate(15);
        return view('participant.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function categoryCreate()
    {
        $events = Event::all();
        return view('participant.categories.create', compact('events'));
    }

    /**
     * Store a newly created category.
     */
    public function categoryStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|in:talent,Q&A,presentation',
            'description' => 'nullable|string',
            'event_id' => 'required|exists:events,id',
            'percentage_weight' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        Category::create($validated);

        return redirect()->route('participant.category.index')->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified category.
     */
    public function categoryShow(Category $category)
    {
        $category->load('event', 'criteria');
        return view('participant.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function categoryEdit(Category $category)
    {
        $events = Event::all();
        return view('participant.categories.edit', compact('category', 'events'));
    }

    /**
     * Update the specified category.
     */
    public function categoryUpdate(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|in:talent,Q&A,presentation',
            'description' => 'nullable|string',
            'event_id' => 'required|exists:events,id',
            'percentage_weight' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        $category->update($validated);

        return redirect()->route('participant.category.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category.
     */
    public function categoryDestroy(Category $category)
    {
        $category->delete();

        return redirect()->route('participant.category.index')->with('success', 'Category deleted successfully.');
    }

    // ==================== CRITERIA ====================

    /**
     * Display a listing of criteria.
     */
    public function criteriaIndex()
    {
        $criteria = Criteria::with('event', 'category')->paginate(15);
        return view('participant.criteria.index', compact('criteria'));
    }

    /**
     * Show the form for creating a new criteria.
     */
    public function criteriaCreate()
    {
        $events = Event::all();
        $categories = Category::all();
        return view('participant.criteria.create', compact('events', 'categories'));
    }

    /**
     * Store a newly created criteria.
     */
    public function criteriaStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'event_id' => 'required|exists:events,id',
            'category_id' => 'required|exists:categories,id',
            'max_score' => 'required|numeric|min:0',
            'weight' => 'required|numeric|min:0',
        ]);

        Criteria::create($validated);

        return redirect()->route('participant.criteria.index')->with('success', 'Criteria created successfully.');
    }

    /**
     * Display the specified criteria.
     */
    public function criteriaShow(Criteria $criterion)
    {
        $criterion->load('event', 'category');
        return view('participant.criteria.show', compact('criterion'));
    }

    /**
     * Show the form for editing the specified criteria.
     */
    public function criteriaEdit(Criteria $criterion)
    {
        $events = Event::all();
        $categories = Category::all();
        return view('participant.criteria.edit', compact('criterion', 'events', 'categories'));
    }

    /**
     * Update the specified criteria.
     */
    public function criteriaUpdate(Request $request, Criteria $criterion)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'event_id' => 'required|exists:events,id',
            'category_id' => 'required|exists:categories,id',
            'max_score' => 'required|numeric|min:0',
            'weight' => 'required|numeric|min:0',
        ]);

        $criterion->update($validated);

        return redirect()->route('participant.criteria.index')->with('success', 'Criteria updated successfully.');
    }

    /**
     * Remove the specified criteria.
     */
    public function criteriaDestroy(Criteria $criterion)
    {
        $criterion->delete();

        return redirect()->route('participant.criteria.index')->with('success', 'Criteria deleted successfully.');
    }

    // ==================== USERS ====================

    /**
     * Display a listing of users (view only).
     */
    public function userIndex()
    {
        $users = User::paginate(15);
        return view('participant.users.index', compact('users'));
    }

    // ==================== SETTINGS ====================

    /**
     * Display settings page for participant.
     */
    public function settings()
    {
        $user = Auth::user();
        $profile = $user->participantProfile;
        return view('participant.settings', compact('user', 'profile'));
    }

    /**
     * Display listing of participant's scores.
     */
    public function scoreIndex()
    {
        $user = Auth::user();
        
        // Load all received scores with relationships
        $scores = $user->receivedScores()
            ->with(['event', 'criteria.category', 'judge'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Compute event statistics
        $eventStats = $user->receivedScores()
            ->select('event_id')
            ->selectRaw('
                SUM(score) as total_score,
                AVG(score) as avg_score,
                COUNT(*) as num_scores,
                COUNT(DISTINCT judge_id) as num_judges
            ')
            ->groupBy('event_id')
            ->with('event')
            ->get()
            ->keyBy('event_id');
        
        return view('participant.scores.index', compact('scores', 'eventStats'));
    }
}
