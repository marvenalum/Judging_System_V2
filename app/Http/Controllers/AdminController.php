<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard() {
        return view('admin.dashboard');
    }

    public function users() {
        return view('admin.users');
    }

    public function settings() {
        return view('admin.settings');
    }



    public function index() {
        if (auth()->user()->role === 'judge') {
            $events = auth()->user()->judgeAssignments()->with('event')->get()->pluck('event');
            return view('judge.events.index', compact('events'));
        } else {
            $events = Event::all();
            return view('admin.events', compact('events'));
        }
    }

    public function show(Event $event) {
        return view('admin.events.show', compact('event'));
    }

    public function create() {
        return view('admin.events.create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'event_description' => 'required|string',
            'event_type' => 'required|in:pageant,contest,competition',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'event_status' => 'required|in:upcoming,ongoing,completed',
        ]);

        $validated['created_by'] = auth()->id();

        Event::create($validated);

        return redirect()->route('admin.event.index')->with('success', 'Event created successfully.');
    }

    public function edit(Event $event) {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event) {
        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'event_description' => 'required|string',
            'event_type' => 'required|in:pageant,contest,competition',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'event_status' => 'required|in:upcoming,ongoing,completed',
        ]);

        $event->update($validated);

        return redirect()->route('admin.event.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event) {
        $event->delete();

        return redirect()->route('admin.event.index')->with('success', 'Event deleted successfully.');
    }
}
