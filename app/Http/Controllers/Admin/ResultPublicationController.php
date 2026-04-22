<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResultPublication;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;

class ResultPublicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ResultPublication::with(['event', 'category']);

        // Filter by event
        if ($request->has('event_id') && $request->event_id) {
            $query->where('event_id', $request->event_id);
        }

        // Filter by category
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $publications = $query->orderBy('created_at', 'desc')->paginate(15);

        $events = Event::all();
        $categories = Category::all();

        return view('admin.result-publications.index', compact('publications', 'events', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $events = Event::where('event_status', 'ongoing')->get();
        $categories = Category::where('status', 'active')->get();

        return view('admin.result-publications.create', compact('events', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Check if publication already exists for this event-category combination
        $existing = ResultPublication::where('event_id', $validated['event_id'])
            ->where('category_id', $validated['category_id'])
            ->first();

        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'A result publication already exists for this event and category combination.');
        }

        $publication = ResultPublication::create([
            'event_id' => $validated['event_id'],
            'category_id' => $validated['category_id'],
            'publication_code' => ResultPublication::generatePublicationCode(),
            'status' => 'draft',
        ]);

        // Compute initial results
        $publication->updateResultsData();

        return redirect()->route('admin.result-publications.show', $publication)
            ->with('success', 'Result publication created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ResultPublication $resultPublication)
    {
        $resultPublication->load(['event', 'category']);

        return view('admin.result-publications.show', compact('resultPublication'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ResultPublication $resultPublication)
    {
        $events = Event::where('event_status', 'ongoing')->get();
        $categories = Category::where('status', 'active')->get();

        return view('admin.result-publications.edit', compact('resultPublication', 'events', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ResultPublication $resultPublication)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:draft,published,archived',
        ]);

        // Check for conflicts if changing event/category
        if ($validated['event_id'] != $resultPublication->event_id ||
            $validated['category_id'] != $resultPublication->category_id) {

            $existing = ResultPublication::where('event_id', $validated['event_id'])
                ->where('category_id', $validated['category_id'])
                ->where('id', '!=', $resultPublication->id)
                ->first();

            if ($existing) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'A result publication already exists for this event and category combination.');
            }
        }

        $resultPublication->update($validated);

        // Recompute results if needed
        if ($resultPublication->status === 'published' && !$resultPublication->published_at) {
            $resultPublication->publish();
        }

        $resultPublication->updateResultsData();

        return redirect()->route('admin.result-publications.show', $resultPublication)
            ->with('success', 'Result publication updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ResultPublication $resultPublication)
    {
        if ($resultPublication->status === 'published') {
            return redirect()->back()
                ->with('error', 'Cannot delete a published result publication. Archive it first.');
        }

        $resultPublication->delete();

        return redirect()->route('admin.result-publications.index')
            ->with('success', 'Result publication deleted successfully.');
    }

    /**
     * Publish the results.
     */
    public function publish(ResultPublication $resultPublication)
    {
        if ($resultPublication->publish()) {
            return redirect()->back()
                ->with('success', 'Results published successfully.');
        }

        return redirect()->back()
            ->with('error', 'Failed to publish results.');
    }

    /**
     * Archive the results.
     */
    public function archive(ResultPublication $resultPublication)
    {
        if ($resultPublication->archive()) {
            return redirect()->back()
                ->with('success', 'Results archived successfully.');
        }

        return redirect()->back()
            ->with('error', 'Failed to archive results.');
    }

    /**
     * Refresh the results data.
     */
    public function refreshResults(ResultPublication $resultPublication)
    {
        if ($resultPublication->updateResultsData()) {
            return redirect()->back()
                ->with('success', 'Results refreshed successfully.');
        }

        return redirect()->back()
            ->with('error', 'Failed to refresh results.');
    }


}
