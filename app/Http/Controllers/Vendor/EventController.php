<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $institution = session('current_institution');
        $events = Event::where('institution_id', $institution->id)->latest()->paginate(10);

        return view('vendor.modules.event.index', compact('events'));
    }

    public function create()
    {
        return view('vendor.modules.event.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ]);

        $institution = session('current_institution');

        Event::create([
            'title' => $validated['title'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'description' => $validated['description'] ?? null,
            'institution_id' => $institution->id,
        ]);

        return redirect()->route('vendor.event.index')
            ->with('success', 'Event created successfully.');
    }

    public function show(Event $event)
    {
        $institution = session('current_institution');

        if ($institution->id !== $event->institution_id) {
            abort(404);
        }

        return view('vendor.modules.event.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $institution = session('current_institution');

        if ($institution->id !== $event->institution_id) {
            abort(404);
        }

        return view('vendor.modules.event.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $institution = session('current_institution');

        if ($institution->id !== $event->institution_id) {
            abort(404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ]);

        $event->update($validated);

        return redirect()->route('vendor.event.index')
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $institution = session('current_institution');

        if ($institution->id !== $event->institution_id) {
            abort(404);
        }

        $event->delete();

        return redirect()->route('vendor.event.index')
            ->with('success', 'Event deleted successfully.');
    }
}
