<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;

class CriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check if accessed via judge routes
        if (request()->routeIs('judge.criteria.index')) {
            // For judges, show only criteria for events they are assigned to
            $assignedEventIds = auth()->user()->judgeAssignments()->pluck('event_id');
            $criteria = Criteria::with('event')
                ->whereIn('event_id', $assignedEventIds)
                ->get();
            return view('judge.criteria.index', compact('criteria'));
        }

        // Default admin view
        $criteria = Criteria::with('event')->get();
        return view('admin.criteria.index', compact('criteria'));
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
            'event_id' => 'required|exists:events,id',
            'category_id' => 'required|exists:categories,id',
            'max_score' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0|max:100',
        ]);

        Criteria::create($request->all());

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
            'event_id' => 'required|exists:events,id',
            'category_id' => 'required|exists:categories,id',
            'max_score' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0|max:100',
        ]);

        $criteria->update($request->all());

        return redirect()->route('admin.criteria.index')->with('success', 'Criterion updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Criteria $criteria)
    {
        $criteria->delete();

        return redirect()->route('admin.criteria.index')->with('success', 'Criteria deleted successfully.');
    }
}
