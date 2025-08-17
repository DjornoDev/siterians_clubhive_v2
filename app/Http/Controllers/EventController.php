<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Event;
use App\Models\EventDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EventController extends Controller
{
    use AuthorizesRequests;

    public function index(Club $club, Request $request)
    {
        Log::info('EventController index called', [
            'club_id' => $club->club_id ?? null,
            'user_id' => Auth::id()
        ]);

        // Initialize default values first to ensure they're always available
        $todayCount = 0;
        $upcomingCount = 0;
        $pastCount = 0;
        $todayEvents = collect()->paginate(9);
        $upcomingEvents = collect()->paginate(9);
        $pastEvents = collect()->paginate(9);
        $events = collect()->paginate(9);
        $eventTypes = collect();

        try {
            $userId = Auth::id();
            $isClubMember = $club->members()->where('tbl_club_membership.user_id', $userId)->exists();
            $isClubAdviser = $club->club_adviser == $userId;

            // Base query for events
            $baseQuery = $club->events()->where('approval_status', 'approved');

            if (!$isClubMember && !$isClubAdviser) {
                $baseQuery->where('event_visibility', 'PUBLIC');
            }

            // Get tab parameter or default to 'today'
            $tab = $request->get('tab', 'today');
            $search = $request->get('search');
            $eventType = $request->get('event_type');
            $status = $request->get('status');

            // Get current time for filtering
            $today = now()->startOfDay();
            $endOfDay = now()->endOfDay();

            Log::info('Count variables initialized', [
                'todayCount' => $todayCount,
                'upcomingCount' => $upcomingCount,
                'pastCount' => $pastCount
            ]);

            // Get total counts for tab badges (without search/filters applied)
            try {
                $todayCount = (clone $baseQuery)->whereBetween('event_date', [$today, $endOfDay])->count();
                $upcomingCount = (clone $baseQuery)->where('event_date', '>', $endOfDay)->count();
                $pastCount = (clone $baseQuery)->where('event_date', '<', $today)->count();

                Log::info('Counts calculated', [
                    'todayCount' => $todayCount,
                    'upcomingCount' => $upcomingCount,
                    'pastCount' => $pastCount
                ]);
            } catch (\Exception $e) {
                // Log error and use defaults
                Log::error('Error calculating event counts: ' . $e->getMessage());
                $todayCount = 0;
                $upcomingCount = 0;
                $pastCount = 0;
            }

            // Create separate queries for each tab
            $todayQuery = clone $baseQuery;
            $todayQuery->whereBetween('event_date', [$today, $endOfDay])->with('documents');

            $upcomingQuery = clone $baseQuery;
            $upcomingQuery->where('event_date', '>', $endOfDay)->with('documents');

            $pastQuery = clone $baseQuery;
            $pastQuery->where('event_date', '<', $today)->with('documents');

            // Apply search and filters to all queries
            $queries = [$todayQuery, $upcomingQuery, $pastQuery];
            foreach ($queries as $query) {
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('event_name', 'LIKE', "%{$search}%")
                            ->orWhere('event_description', 'LIKE', "%{$search}%")
                            ->orWhere('event_location', 'LIKE', "%{$search}%");
                    });
                }

                if ($status) {
                    $query->where('approval_status', $status);
                }
            }

            // Get paginated results for each tab
            $todayEvents = $todayQuery->orderBy('event_date', 'asc')->paginate(9, ['*'], 'today_page');
            $upcomingEvents = $upcomingQuery->orderBy('event_date', 'asc')->paginate(9, ['*'], 'upcoming_page');
            $pastEvents = $pastQuery->orderBy('event_date', 'desc')->paginate(9, ['*'], 'past_page');

            // Get the main events collection based on current tab
            switch ($tab) {
                case 'upcoming':
                    $events = $upcomingEvents;
                    break;
                case 'past':
                    $events = $pastEvents;
                    break;
                default:
                    $events = $todayEvents;
                    break;
            }

            // Get event types for filter - empty for now since event_type column doesn't exist
            $eventTypes = collect();

            // Final log before returning view
            Log::info('About to return view with variables', [
                'todayCount' => $todayCount,
                'upcomingCount' => $upcomingCount,
                'pastCount' => $pastCount
            ]);
        } catch (\Exception $e) {
            Log::error('EventController index error: ' . $e->getMessage(), [
                'club_id' => $club->club_id ?? null,
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            // Use defaults that were already initialized
            $tab = 'today';
            $search = null;
            $eventType = null;
            $status = null;
            $isClubMember = false;
            $isClubAdviser = false;
        }

        return view('clubs.events.index', compact(
            'club',
            'events',
            'isClubMember',
            'isClubAdviser',
            'tab',
            'search',
            'eventType',
            'status',
            'todayEvents',
            'upcomingEvents',
            'pastEvents',
            'todayCount',
            'upcomingCount',
            'pastCount',
            'eventTypes'
        ));
    }

    public function globalIndex()
    {
        $clubs = Club::all();
        $userId = Auth::id();
        $clubsUserIsAssociatedWith = [];

        if (Auth::check()) {
            // Get all clubs the user is associated with (as member or adviser)
            $clubsUserIsAssociatedWith = Auth::user()->getAllAssociatedClubIds();
        }

        // Get events query with proper visibility filtering
        $events = Event::with(['club', 'documents'])
            ->where('approval_status', 'approved') // Only show approved events
            ->where(function ($query) use ($userId, $clubsUserIsAssociatedWith) {
                // Get all PUBLIC events
                $query->where('event_visibility', 'PUBLIC');

                // Add CLUB_ONLY events where the user is a member or is the organizer
                if (Auth::check()) {
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

        // Debug logging
        Log::info('Event creation attempt', [
            'user_id' => Auth::id(),
            'club_id' => $club->club_id,
            'has_file' => $request->hasFile('supporting_documents'),
            'form_data' => $request->except(['supporting_documents']),
        ]);

        $validated = $request->validate([
            'event_name' => 'required|string',
            'event_description' => 'nullable|string',
            'event_date' => 'required|date|after_or_equal:today',
            'event_time' => 'nullable|string',
            'event_visibility' => 'required|in:PUBLIC,CLUB_ONLY',
            'event_location' => 'nullable|string',
            'supporting_documents' => 'nullable|array|max:5', // Max 5 files
            'supporting_documents.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png,txt,ppt,pptx,xls,xlsx,zip,rar|max:10240', // 10MB max per file
        ]);

        // Handle multiple file uploads
        $uploadedFiles = [];
        if ($request->hasFile('supporting_documents')) {
            foreach ($request->file('supporting_documents') as $file) {
                $filePath = $file->store('event-documents', 'public');
                $uploadedFiles[] = [
                    'document_path' => $filePath,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'uploaded_at' => now(),
                ];

                Log::info('File uploaded successfully', [
                    'path' => $filePath,
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        // Check if current user is SSLG adviser (club ID 1)
        $sslgClub = Club::find(1);
        $isSSLGAdviser = $sslgClub && Auth::id() === $sslgClub->club_adviser;

        // Auto-approve if SSLG adviser, otherwise set to pending
        $approvalStatus = $isSSLGAdviser ? 'approved' : 'pending';
        $approvedBy = $isSSLGAdviser ? Auth::id() : null;
        $approvedAt = $isSSLGAdviser ? now() : null;

        try {
            $event = $club->events()->create([
                'event_name' => $validated['event_name'],
                'event_description' => $validated['event_description'],
                'organizer_id' => Auth::id(),
                'event_date' => $validated['event_date'],
                'event_time' => $validated['event_time'],
                'event_visibility' => $validated['event_visibility'],
                'event_location' => $validated['event_location'],
                'approval_status' => $approvalStatus,
                'approved_by' => $approvedBy,
                'approved_at' => $approvedAt,
            ]);

            // Save uploaded documents to the new documents table
            if (!empty($uploadedFiles)) {
                foreach ($uploadedFiles as $fileData) {
                    $event->documents()->create($fileData);
                }
            }

            Log::info('Event created successfully', [
                'event_id' => $event->event_id,
                'event_name' => $event->event_name,
                'files_count' => count($uploadedFiles),
            ]);

            $message = $isSSLGAdviser ?
                'Event created and automatically approved.' :
                'Event created successfully and is pending approval.';

            return redirect()->route('clubs.events.index', $club)
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Event creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create event: ' . $e->getMessage());
        }
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
            'event_location' => 'nullable|string',
            'supporting_documents' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,txt,ppt,pptx,xls,xlsx,zip,rar|max:10240', // 10MB max
        ]);

        $updateData = [
            'event_name' => $validated['event_name'],
            'event_description' => $validated['event_description'],
            'event_date' => $validated['event_date'],
            'event_time' => $validated['event_time'],
            'event_visibility' => $validated['event_visibility'],
            'event_location' => $validated['event_location'],
        ];

        // Handle file upload if new file is provided
        if ($request->hasFile('supporting_documents')) {
            // Delete old file if it exists
            if ($event->supporting_documents && Storage::disk('public')->exists($event->supporting_documents)) {
                Storage::disk('public')->delete($event->supporting_documents);
            }

            $file = $request->file('supporting_documents');
            $originalName = $file->getClientOriginalName();
            $mimeType = $file->getMimeType();
            $fileSize = $file->getSize();

            // Store in event-documents directory
            $supportingDocumentsPath = $file->store('event-documents', 'public');

            $updateData['supporting_documents'] = $supportingDocumentsPath;
            $updateData['supporting_documents_original_name'] = $originalName;
            $updateData['supporting_documents_mime_type'] = $mimeType;
            $updateData['supporting_documents_size'] = $fileSize;
        }

        // If event is being edited and it was previously approved, reset to pending
        // BUT if the current user is SSLG adviser, auto-approve again
        $sslgClub = Club::find(1);
        $isSSLGAdviser = $sslgClub && Auth::id() === $sslgClub->club_adviser;

        if ($event->approval_status === 'approved') {
            if ($isSSLGAdviser) {
                // SSLG adviser's edits are auto-approved
                $updateData['approval_status'] = 'approved';
                $updateData['approved_at'] = now();
                $updateData['approved_by'] = Auth::id();
                $updateData['rejection_reason'] = null;
            } else {
                // Others need re-approval
                $updateData['approval_status'] = 'pending';
                $updateData['approved_at'] = null;
                $updateData['approved_by'] = null;
                $updateData['rejection_reason'] = null;
            }
        }

        $event->update($updateData);

        if ($isSSLGAdviser) {
            $message = 'Event updated and automatically approved.';
        } elseif ($event->approval_status === 'pending') {
            $message = 'Event updated and submitted for re-approval.';
        } else {
            $message = 'Event updated successfully.';
        }

        return redirect()->route('clubs.events.index', $club)->with('success', $message);
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
        $userId = Auth::id();
        $clubsUserIsAssociatedWith = [];

        if (Auth::check()) {
            // Get all clubs the user is associated with (as member or adviser)
            $clubsUserIsAssociatedWith = Auth::user()->getAllAssociatedClubIds();
        }

        // Get events with proper visibility filtering
        $eventsQuery = Event::where('approval_status', 'approved') // Only approved events
            ->where(function ($query) use ($userId, $clubsUserIsAssociatedWith) {
                // Get all PUBLIC events
                $query->where('event_visibility', 'PUBLIC');

                // Add CLUB_ONLY events where the user is a member or is the organizer
                if (Auth::check()) {
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

    /**
     * Show pending events for SSLG adviser
     */
    public function pendingEvents()
    {
        // Check if user is SSLG adviser (club ID 1)
        $sslgClub = Club::find(1);
        if (!$sslgClub || Auth::id() !== $sslgClub->club_adviser) {
            abort(403, 'Unauthorized. Only SSLG adviser can view pending events.');
        }

        $pendingEvents = Event::with(['club', 'organizer', 'documents'])
            ->where('approval_status', 'pending')
            ->latest()
            ->paginate(10);

        return view('events.pending', compact('pendingEvents'));
    }

    /**
     * Show event details for approval
     */
    public function showForApproval(Event $event)
    {
        // Check if user is SSLG adviser (club ID 1)
        $sslgClub = Club::find(1);
        if (!$sslgClub || Auth::id() !== $sslgClub->club_adviser) {
            abort(403, 'Unauthorized. Only SSLG adviser can view pending events.');
        }

        // Only show pending events
        if ($event->approval_status !== 'pending') {
            abort(404, 'Event not found or already processed.');
        }

        return view('events.approval-details', compact('event'));
    }

    /**
     * Approve an event
     */
    public function approve(Event $event)
    {
        // Check if user is SSLG adviser (club ID 1)
        $sslgClub = Club::find(1);
        if (!$sslgClub || Auth::id() !== $sslgClub->club_adviser) {
            abort(403, 'Unauthorized. Only SSLG adviser can approve events.');
        }

        // Only approve pending events
        if ($event->approval_status !== 'pending') {
            return redirect()->back()->with('error', 'Event has already been processed.');
        }

        $event->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'rejection_reason' => null, // Clear any previous rejection reason
        ]);

        return redirect()->route('events.pending')->with('success', 'Event approved successfully.');
    }

    /**
     * Reject an event with reason
     */
    public function reject(Request $request, Event $event)
    {
        // Check if user is SSLG adviser (club ID 1)
        $sslgClub = Club::find(1);
        if (!$sslgClub || Auth::id() !== $sslgClub->club_adviser) {
            abort(403, 'Unauthorized. Only SSLG adviser can reject events.');
        }

        // Only reject pending events
        if ($event->approval_status !== 'pending') {
            return redirect()->back()->with('error', 'Event has already been processed.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:10|max:500',
        ]);

        $event->update([
            'approval_status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'approved_at' => null,
            'approved_by' => null,
        ]);

        return redirect()->route('events.pending')->with('success', 'Event rejected successfully.');
    }

    /**
     * Download supporting documents
     */
    public function downloadSupportingDocument(Event $event)
    {
        // Check if user can view this event or is SSLG adviser
        $sslgClub = Club::find(1);
        if (
            !$event->canBeViewedBy(Auth::user()) &&
            (!$sslgClub || Auth::id() !== $sslgClub->club_adviser)
        ) {
            abort(403, 'Unauthorized.');
        }

        if (!$event->supporting_documents || !Storage::disk('public')->exists($event->supporting_documents)) {
            abort(404, 'Supporting document not found.');
        }

        return response()->download(
            storage_path('app/public/' . $event->supporting_documents),
            $event->supporting_documents_original_name
        );
    }

    public function downloadDocument(EventDocument $document)
    {
        $event = $document->event;

        // Check if user can view this event or is SSLG adviser
        $sslgClub = Club::find(1);
        if (
            !$event->canBeViewedBy(Auth::user()) &&
            (!$sslgClub || Auth::id() !== $sslgClub->club_adviser)
        ) {
            abort(403, 'Unauthorized.');
        }

        if (!Storage::disk('public')->exists($document->document_path)) {
            abort(404, 'Document not found.');
        }

        return response()->download(
            storage_path('app/public/' . $document->document_path),
            $document->original_name
        );
    }

    /**
     * Show user's created events with their approval status
     */
    public function myEvents()
    {
        $events = Event::with(['club', 'documents'])
            ->where('organizer_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('events.my-events', compact('events'));
    }
}
