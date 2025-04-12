<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EventController extends Controller
{
    use AuthorizesRequests;

    public function index(Club $club)
    {
        $events = $club->events()->latest()->paginate(10); // Fetch events for the club, paginated
        return view('clubs.events.index', compact('club', 'events'));
    }

    public function globalIndex()
    {
        $clubs = Club::all();
        $events = Event::with('club')
            ->where('event_visibility', 'PUBLIC')
            ->orderBy('event_date')
            ->paginate(10);

        return view('events.index', [
            'events' => $events,
            'clubs' => $clubs
        ]);
    }

    public function show(Club $club, Event $event)
    {
        return view('clubs.events.index', compact('club', 'event'));
    }

    public function create(Club $club)
    {
        $this->authorize('create', [Event::class, $club]);
        return view('clubs.events.create', compact('club'));
    }

    public function store(Request $request, Club $club)
    {
        $this->authorize('create', [Event::class, $club]);

        $validated = $request->validate([
            'event_name' => 'required|string',
            'event_description' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'nullable|string',
            'event_visibility' => 'required|in:PUBLIC,CLUB_ONLY',
            'event_location' => 'required|string',
        ]);

        $club->events()->create([
            'event_name' => $validated['event_name'],
            'event_description' => $validated['event_description'],
            'organizer_id' => auth()->id(),
            'event_date' => $validated['event_date'],
            'event_time' => $validated['event_time'],
            'event_visibility' => $validated['event_visibility'],
            'event_location' => $validated['event_location'],
        ]);

        return redirect()->route('clubs.events.index', $club);
    }

    public function edit(Club $club, Event $event)
    {
        $this->authorize('update', $event);
        return view('clubs.events.partials.edit-event-modal', compact('club', 'event'));
    }

    public function update(Request $request, Club $club, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'event_name' => 'required|string',
            'event_description' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'nullable|string',
            'event_visibility' => 'required|in:PUBLIC,CLUB_ONLY',
            'event_location' => 'required|string',
        ]);

        $event->update($validated);
        return redirect()->route('clubs.events.index', $club);
    }

    public function destroy(Club $club, Event $event)
    {
        $this->authorize('delete', $event);
        $event->delete();
        return redirect()->route('clubs.events.index', $club);
    }
}
