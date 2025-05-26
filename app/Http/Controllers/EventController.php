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
        $userId = auth()->id();
        $isClubMember = $club->members()->where('tbl_club_membership.user_id', $userId)->exists();
        $isClubAdviser = $club->club_adviser == $userId;

        // If the user is a club member or adviser, show all events
        // Otherwise, show only PUBLIC events
        $eventsQuery = $club->events();

        if (!$isClubMember && !$isClubAdviser) {
            // If not a member or adviser, only show public events
            $eventsQuery->where('event_visibility', 'PUBLIC');
        } else {
            // For club members/adviser, we show all events (both PUBLIC and CLUB_ONLY)
            // No need for additional filtering here
        }

        $events = $eventsQuery->latest()->paginate(10); // Fetch filtered events, paginated
        return view('clubs.events.index', compact('club', 'events', 'isClubMember', 'isClubAdviser'));
    }

    public function globalIndex()
    {
        $clubs = Club::all();
        $userId = auth()->id();
        $clubsUserIsAssociatedWith = [];

        if (auth()->check()) {
            // Get all clubs the user is associated with (as member or adviser)
            $clubsUserIsAssociatedWith = auth()->user()->getAllAssociatedClubIds();
        }

        // Get events query with proper visibility filtering
        $events = Event::with('club')
            ->where(function ($query) use ($userId, $clubsUserIsAssociatedWith) {
                // Get all PUBLIC events
                $query->where('event_visibility', 'PUBLIC');

                // Add CLUB_ONLY events where the user is a member or is the organizer
                if (auth()->check()) {
                    $query->orWhere(function ($q) use ($userId, $clubsUserIsAssociatedWith) {
                        $q->where('event_visibility', 'CLUB_ONLY')
                            ->where(function ($innerQuery) use ($userId, $clubsUserIsAssociatedWith) {
                                // Event organizer can see their own events
                                $innerQuery->where('organizer_id', $userId)
                                    // Club members can see events from their clubs
                                    ->orWhereIn('club_id', $clubsUserIsAssociatedWith);
                            });
                    });
                }
            })
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
            'event_date' => 'required|date|after_or_equal:today',
            'event_time' => 'nullable|string',
            'event_visibility' => 'required|in:PUBLIC,CLUB_ONLY',
            'event_location' => 'nullable|string', // Changed from required to nullable
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
            'event_date' => 'required|date|after_or_equal:today',
            'event_time' => 'nullable|string',
            'event_visibility' => 'required|in:PUBLIC,CLUB_ONLY',
            'event_location' => 'nullable|string', // Changed from required to nullable
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

    public function checkGlobalEventChanges(Request $request)
    {
        $currentChecksum = $request->query('checksum', '');
        $userId = auth()->id();
        $clubsUserIsAssociatedWith = [];

        if (auth()->check()) {
            // Get all clubs the user is associated with (as member or adviser)
            $clubsUserIsAssociatedWith = auth()->user()->getAllAssociatedClubIds();
        }

        // Get events with proper visibility filtering
        $eventsQuery = Event::where(function ($query) use ($userId, $clubsUserIsAssociatedWith) {
            // Get all PUBLIC events
            $query->where('event_visibility', 'PUBLIC');

            // Add CLUB_ONLY events where the user is a member or is the organizer
            if (auth()->check()) {
                $query->orWhere(function ($q) use ($userId, $clubsUserIsAssociatedWith) {
                    $q->where('event_visibility', 'CLUB_ONLY')
                        ->where(function ($innerQuery) use ($userId, $clubsUserIsAssociatedWith) {
                            // Event organizer can see their own events
                            $innerQuery->where('organizer_id', $userId)
                                // Club members/advisers can see events from their clubs
                                ->orWhereIn('club_id', $clubsUserIsAssociatedWith);
                        });
                });
            }
        });

        $events = $eventsQuery->get();

        // Generate a new checksum that includes event IDs and updated timestamps
        // This will change if events are added, edited, or deleted
        $newChecksum = md5(json_encode($events->pluck('event_id')->merge($events->pluck('updated_at'))));

        // Compare the checksums to determine if there are any changes
        $hasChanges = $currentChecksum !== $newChecksum;

        return response()->json([
            'hasChanges' => $hasChanges
        ]);
    }
}
