<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('event')->paginate(10);
        if (request()->routeIs('judge.category.*')) {
            return view('judge.category.index', compact('categories'));
        }
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|in:talent,Q&A,presentation',
            'description' => 'nullable|string',
            'event_id' => 'required|exists:events,id',
            'percentage_weight' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        Category::create($request->all());

        return redirect()->route('admin.category.index')->with('success', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        $category->load('event');
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|in:talent,Q&A,presentation',
            'description' => 'nullable|string',
            'event_id' => 'required|exists:events,id',
            'percentage_weight' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        $category->update($request->all());

        return redirect()->route('admin.category.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.category.index')->with('success', 'Category deleted successfully.');
    }
}
