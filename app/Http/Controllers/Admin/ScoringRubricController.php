<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScoringRubric;
use App\Models\Criteria;
use Illuminate\Http\Request;

class ScoringRubricController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ScoringRubric::with(['criteria.category.event']);

        // Filter by criteria if specified
        if ($request->has('criteria_id') && $request->criteria_id) {
            $query->where('criteria_id', $request->criteria_id);
        }

        $rubrics = $query->paginate(15);
        $criteria = Criteria::with('category.event')->get();

        return view('admin.scoring-rubrics.index', compact('rubrics', 'criteria'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $criteria = Criteria::with('category.event')->where('status', 'active')->get();
        return view('admin.scoring-rubrics.create', compact('criteria'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'criteria_id' => 'required|exists:criteria,id',
            'level' => 'required|integer|min:1|max:10',
            'score_range_min' => 'required|numeric|min:0',
            'score_range_max' => 'required|numeric|min:0|gte:score_range_min',
            'description' => 'required|string|max:1000',
            'detailed_criteria' => 'nullable|string|max:2000',
        ]);

        ScoringRubric::create($validated);

        return redirect()->route('admin.scoring-rubrics.index')
            ->with('success', 'Scoring rubric created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ScoringRubric $scoringRubric)
    {
        $scoringRubric->load(['criteria.category.event']);
        return view('admin.scoring-rubrics.show', compact('scoringRubric'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ScoringRubric $scoringRubric)
    {
        $criteria = Criteria::with('category.event')->where('status', 'active')->get();
        return view('admin.scoring-rubrics.edit', compact('scoringRubric', 'criteria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ScoringRubric $scoringRubric)
    {
        $validated = $request->validate([
            'criteria_id' => 'required|exists:criteria,id',
            'level' => 'required|integer|min:1|max:10',
            'score_range_min' => 'required|numeric|min:0',
            'score_range_max' => 'required|numeric|min:0|gte:score_range_min',
            'description' => 'required|string|max:1000',
            'detailed_criteria' => 'nullable|string|max:2000',
        ]);

        $scoringRubric->update($validated);

        return redirect()->route('admin.scoring-rubrics.index')
            ->with('success', 'Scoring rubric updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ScoringRubric $scoringRubric)
    {
        $scoringRubric->delete();

        return redirect()->route('admin.scoring-rubrics.index')
            ->with('success', 'Scoring rubric deleted successfully.');
    }
}
  

