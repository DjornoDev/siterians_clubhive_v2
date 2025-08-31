<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\ActionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\User;
use App\Models\Event;
use App\Models\ClubMembership;
use App\Models\Post;
use App\Models\Election;
use App\Models\Candidate;
use App\Models\Vote;
use App\Models\ClubJoinRequest;

class ClubController extends Controller
{
    public function index()
    {
        $clubs = Club::with([
            'adviser',
            'members' => function ($query) {
                $query->where('tbl_club_membership.user_id', auth()->id()); // Add table prefix
            },
            'joinRequests' => function ($query) {
                $query->where('user_id', auth()->id());
            }
        ])->get();

        return view('clubs.index-all', compact('clubs'));
    }

    public function toggleHuntingDay(Club $club)
    {
        // Allow both the club adviser and any admin to toggle hunting day
        abort_if(
            (auth()->id() !== $club->club_adviser && auth()->user()->role !== 'ADMIN') ||
                $club->club_id !== 1,
            403
        );

        // Get the new status based on club 1's current state
        $newStatus = !$club->is_club_hunting_day;

        // Update all clubs to the new status
        Club::query()->update(['is_club_hunting_day' => $newStatus]);

        return response()->json(['status' => 'success']);
    }

    public function updateSettings(Request $request, Club $club)
    {
        abort_if(auth()->id() !== $club->club_adviser, 403);
        $validated = $request->validate([
            'club_name' => 'required|string|max:255',
            'club_description' => 'nullable|string',
            'club_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'club_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        // Handle logo update
        if ($request->hasFile('club_logo')) {
            // Delete old logo
            if ($club->club_logo) {
                Storage::disk('public')->delete($club->club_logo);
            }
            // Store new logo
            $logoPath = $request->file('club_logo')->store('club-logos', 'public');
            $validated['club_logo'] = $logoPath;
        } else {
            unset($validated['club_logo']);
        }

        // Handle banner update
        if ($request->hasFile('club_banner')) {
            // Delete old banner
            if ($club->club_banner) {
                Storage::disk('public')->delete($club->club_banner);
            }
            // Store new banner
            $bannerPath = $request->file('club_banner')->store('club-banners', 'public');
            $validated['club_banner'] = $bannerPath;
        } else {
            unset($validated['club_banner']);
        }

        $club->update($validated);
        return back()->with('success', 'Club settings updated!');
    }

    public function join(Club $club)
    {
        $user = auth()->user();

        // Check if user is already a member
        if ($user->joinedClubs()->where('tbl_club_membership.club_id', $club->club_id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'You are already a member of this club!'
            ], 409);
        }

        // Check if user already has a pending request
        $existingRequest = $user->clubJoinRequests()->where('club_id', $club->club_id)->first();

        if ($existingRequest) {
            if ($existingRequest->status === 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'You already have a pending request for this club!',
                    'has_pending' => true
                ], 409);
            } elseif ($existingRequest->status === 'rejected') {
                // Delete the old rejected request and allow new request
                $existingRequest->delete();
            }
        }

        // If club requires approval, create a join request
        if ($club->requires_approval) {
            ClubJoinRequest::create([
                'club_id' => $club->club_id,
                'user_id' => $user->user_id,
                'status' => 'pending',
                'message' => 'Join request for ' . $club->club_name
            ]);

            // Log join request
            ActionLog::create_log(
                'club_membership',
                'join_requested',
                "Requested to join club: {$club->club_name}",
                [
                    'club_id' => $club->club_id,
                    'club_name' => $club->club_name,
                    'status' => 'pending'
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Join request sent! Wait for club adviser approval.',
                'requires_approval' => true
            ]);
        }

        // If no approval required, add directly as member
        $user->joinedClubs()->attach($club->club_id, [
            'club_role' => 'MEMBER',
            'joined_date' => now(),
            'club_accessibility' => null
        ]);

        // Log direct club join
        ActionLog::create_log(
            'club_membership',
            'joined',
            "Joined club: {$club->club_name}",
            [
                'club_id' => $club->club_id,
                'club_name' => $club->club_name,
                'club_role' => 'MEMBER'
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Successfully joined the club!',
            'requires_approval' => false
        ]);
    }

    public function getNonMembers(Club $club, Request $request)
    {
        $request->validate(['search' => 'nullable|string|min:2']);

        $students = User::where('role', 'STUDENT')
            ->whereDoesntHave('joinedClubs', fn($q) => $q->where('tbl_club_membership.club_id', $club->club_id))
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%")
                        ->orWhere('email', 'like', "%{$request->search}%");
                });
            })
            ->with(['section.schoolClass'])
            ->limit(10)
            ->get();

        // Add display-safe properties to each student
        foreach ($students as $student) {
            $student->display_grade = $student->section && $student->section->schoolClass ?
                $student->section->schoolClass->grade_level : 'N/A';
            $student->display_section = $student->section ?
                $student->section->section_name : 'N/A';
        }

        return response()->json($students);
    }

    public function addMembers(Request $request, Club $club)
    {
        abort_if(auth()->id() !== $club->club_adviser, 403);

        $validated = $request->validate([
            'user_ids' => 'required|string',
        ]);

        $userIds = array_unique(explode(',', $validated['user_ids']));

        // First, check if all users exist
        $existingUsers = User::whereIn('user_id', $userIds)->pluck('user_id')->toArray();
        $nonExistentUsers = array_diff($userIds, $existingUsers);

        if (!empty($nonExistentUsers)) {
            return response()->json([
                'message' => 'Some users do not exist',
                'invalid_users' => $nonExistentUsers
            ], 422);
        }

        // Then check which users are already members
        $existingMembers = $club->members()
            ->whereIn('tbl_users.user_id', $userIds)
            ->pluck('tbl_users.user_id')
            ->toArray();

        if (!empty($existingMembers)) {
            return response()->json([
                'message' => 'Some users are already members',
                'invalid_users' => $existingMembers
            ], 422);
        }

        // If we get here, all users exist and none are already members
        $club->members()->attach($userIds, [
            'club_role' => 'MEMBER',
            'joined_date' => now(),
        ]);

        return response()->json(['message' => 'Members added successfully']);
    }

    public function updateMember(Request $request, Club $club, User $user)
    {
        abort_if(auth()->id() !== $club->club_adviser, 403);

        $validated = $request->validate([
            'club_position' => 'nullable|string|max:255',
            'manage_posts' => 'nullable|boolean',
            'manage_events' => 'nullable|boolean',
        ]);

        // Explicitly cast to boolean for JSON storage
        $club->members()->updateExistingPivot($user->user_id, [
            'club_position' => $validated['club_position'],
            'club_accessibility' => [
                'manage_posts' => (bool) ($validated['manage_posts'] ?? false),
                'manage_events' => (bool) ($validated['manage_events'] ?? false)
            ]
        ]);

        return response()->json(['message' => 'Member updated successfully']);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'club_name' => 'required|string|max:255',
                'club_adviser' => 'required|exists:tbl_users,user_id',
                'club_description' => 'required|string|max:1000',
                'category' => 'required|in:academic,sports,service',
                'requires_approval' => 'boolean',
                'club_logo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Max 2MB
                'club_banner' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // Max 5MB
            ]);

            $logoPath = $request->file('club_logo')->store('club-logos', 'public');
            $bannerPath = $request->file('club_banner')->store('club-banners', 'public');

            $club = Club::create([
                'club_name' => $validated['club_name'],
                'club_adviser' => $validated['club_adviser'],
                'club_description' => $validated['club_description'],
                'category' => $validated['category'],
                'requires_approval' => $validated['requires_approval'] ?? true,
                'club_logo' => $logoPath,
                'club_banner' => $bannerPath,
            ]);

            // Log club creation
            ActionLog::create_log(
                'club_management',
                'created',
                "Created new club: {$club->club_name}",
                [
                    'club_id' => $club->club_id,
                    'club_name' => $club->club_name,
                    'club_adviser' => $validated['club_adviser'],
                    'category' => $validated['category']
                ]
            );

            return redirect()->route('admin.clubs.index')->with('success', 'Club created successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput()->with('error', 'Validation failed');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error creating club: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Club $club)
    {
        try {
            $validated = $request->validate([
                'club_name' => 'required|string|max:255',
                'club_adviser' => 'required|exists:tbl_users,user_id',
                'club_description' => 'required|string|max:1000',
                'category' => 'required|in:academic,sports,service',
                'requires_approval' => 'boolean',
                'club_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'club_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            ]);

            // Handle logo update
            if ($request->hasFile('club_logo')) {
                // Delete old logo
                if ($club->club_logo) {
                    Storage::disk('public')->delete($club->club_logo);
                }
                // Store new logo
                $logoPath = $request->file('club_logo')->store('club-logos', 'public');
                $validated['club_logo'] = $logoPath;
            } else {
                unset($validated['club_logo']);
            }

            // Handle banner update
            if ($request->hasFile('club_banner')) {
                // Delete old banner
                if ($club->club_banner) {
                    Storage::disk('public')->delete($club->club_banner);
                }
                // Store new banner
                $bannerPath = $request->file('club_banner')->store('club-banners', 'public');
                $validated['club_banner'] = $bannerPath;
            } else {
                unset($validated['club_banner']);
            }

            $club->update($validated);

            // Log club update
            ActionLog::create_log(
                'club_management',
                'updated',
                "Updated club: {$club->club_name}",
                [
                    'club_id' => $club->club_id,
                    'club_name' => $club->club_name,
                    'updated_fields' => array_keys($validated)
                ]
            );

            return redirect()->route('admin.clubs.index')->with('success', 'Club updated successfully');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error updating club: ' . $e->getMessage());
        }
    }

    public function destroy(Club $club)
    {
        try {
            $clubName = $club->club_name;
            $clubId = $club->club_id;

            // Delete associated files
            if ($club->club_logo) {
                Storage::disk('public')->delete($club->club_logo);
            }
            if ($club->club_banner) {
                Storage::disk('public')->delete($club->club_banner);
            }

            $club->delete();

            // Log club deletion
            ActionLog::create_log(
                'club_management',
                'deleted',
                "Deleted club: {$clubName}",
                [
                    'club_id' => $clubId,
                    'club_name' => $clubName
                ]
            );

            return redirect()->route('admin.clubs.index')->with('success', 'Club deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting club: ' . $e->getMessage());
        }
    }

    public function verifyAndDelete(Request $request, Club $club)
    {
        // Validate request
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = auth()->user();

        // Check if password is correct
        if (!password_verify($request->password, $user->password)) {
            return response()->json([
                'message' => 'Incorrect password'
            ], 401);
        }

        // Password verified, now delete the club
        try {
            // Delete associated files
            if ($club->club_logo) {
                Storage::disk('public')->delete($club->club_logo);
            }
            if ($club->club_banner) {
                Storage::disk('public')->delete($club->club_banner);
            }

            // Manually delete related records in the correct order to avoid foreign key constraint issues
            // Start with the most dependent tables and work backwards

            // 1. Delete vote details first (they reference candidates)
            \App\Models\VoteDetail::whereHas('vote', function ($query) use ($club) {
                $query->whereHas('election', function ($electionQuery) use ($club) {
                    $electionQuery->where('club_id', $club->club_id);
                });
            })->delete();

            // 2. Delete votes (they reference elections)
            \App\Models\Vote::whereHas('election', function ($query) use ($club) {
                $query->where('club_id', $club->club_id);
            })->delete();

            // 3. Delete candidates (they reference elections)
            \App\Models\Candidate::whereHas('election', function ($query) use ($club) {
                $query->where('club_id', $club->club_id);
            })->delete();

            // 4. Delete elections
            $club->elections()->delete();

            // 5. Delete club questions and answers
            \App\Models\ClubQuestionAnswer::whereHas('clubQuestion', function ($query) use ($club) {
                $query->where('club_id', $club->club_id);
            })->delete();
            $club->questions()->delete();

            // 6. Delete club join requests
            $club->joinRequests()->delete();

            // 7. Delete club memberships
            $club->memberships()->delete();

            // 8. Delete event documents
            \App\Models\EventDocument::whereHas('event', function ($query) use ($club) {
                $query->where('club_id', $club->club_id);
            })->delete();

            // 9. Delete events
            $club->events()->delete();

            // 10. Delete post documents and images
            \App\Models\PostDocument::whereHas('post', function ($query) use ($club) {
                $query->where('club_id', $club->club_id);
            })->delete();
            \App\Models\PostImage::whereHas('post', function ($query) use ($club) {
                $query->where('club_id', $club->club_id);
            })->delete();

            // 11. Delete posts
            $club->posts()->delete();

            // 12. Finally delete the club
            $club->delete();

            return response()->json([
                'message' => 'Club deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting club: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Club $club)
    {
        $todayEvents = $club->events()
            ->with('documents')
            ->whereDate('event_date', today())
            ->orderBy('event_time')
            ->get();

        $upcomingEvents = $club->events()
            ->with('documents')
            ->where('event_date', '>', today())
            ->orderBy('event_date')
            ->take(5) // Adjust the number of upcoming events to show
            ->get();

        $userId = auth()->id();
        $isClubMember = $club->members()->where('tbl_club_membership.user_id', $userId)->exists();
        $isClubAdviser = $club->club_adviser == $userId;

        // If the user is a club member or adviser, show all posts
        // Otherwise, show only PUBLIC posts
        $postsQuery = $club->posts();

        if (!$isClubMember && !$isClubAdviser) {
            // If not a member or adviser, only show public posts
            $postsQuery->where('post_visibility', 'PUBLIC');
        } else {
            // For club members/adviser, we show all posts (both PUBLIC and CLUB_ONLY)
            // No need for additional filtering here since they can see all posts in this club
            // $postsQuery will get all posts for this club
        }

        return view('clubs.index', [
            'club' => $club->loadCount('members'),
            'posts' => $postsQuery->with(['author', 'images', 'documents'])->latest()->paginate(10),
            'todayEvents' => $todayEvents,
            'upcomingEvents' => $upcomingEvents,
            'isClubMember' => $isClubMember,
            'isClubAdviser' => $isClubAdviser,
        ]);
    }

    public function checkPostChanges(Request $request, Club $club)
    {
        $currentChecksum = $request->query('checksum', '');

        // Get all posts for this club
        $posts = $club->posts()->latest()->get();

        // Generate a new checksum that includes post IDs and updated timestamps
        // This will change if posts are added, edited, or deleted
        $newChecksum = md5(json_encode($posts->pluck('post_id')->merge($posts->pluck('updated_at'))));

        // Compare the checksums to determine if there are any changes
        $hasChanges = $currentChecksum !== $newChecksum;

        return response()->json([
            'hasChanges' => $hasChanges
        ]);
    }

    public function checkEventChanges(Request $request, Club $club)
    {
        $currentChecksum = $request->query('checksum', '');

        // Get all events for this club
        $events = $club->events()->with('documents')->get();

        // Generate a new checksum that includes event IDs and updated timestamps
        // This will change if events are added, edited, or deleted
        $newChecksum = md5(json_encode($events->pluck('event_id')->merge($events->pluck('updated_at'))));

        // Compare the checksums to determine if there are any changes
        $hasChanges = $currentChecksum !== $newChecksum;

        return response()->json([
            'hasChanges' => $hasChanges
        ]);
    }

    public function events(Club $club, Request $request)
    {
        Log::info('ClubController events called', [
            'club_id' => $club->club_id ?? null,
            'user_id' => Auth::id()
        ]);

        // Initialize default values first to ensure they're always available
        $todayCount = 0;
        $upcomingCount = 0;
        $pastCount = 0;

        // Create empty paginated collections using Laravel's paginator
        $emptyPaginator = new LengthAwarePaginator(
            [], // items
            0,  // total
            9,  // perPage
            1,  // currentPage
            ['path' => request()->url(), 'pageName' => 'page']
        );

        $todayEvents = clone $emptyPaginator;
        $upcomingEvents = clone $emptyPaginator;
        $pastEvents = clone $emptyPaginator;
        $events = clone $emptyPaginator;
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
            $todayQuery->whereBetween('event_date', [$today, $endOfDay]);

            $upcomingQuery = clone $baseQuery;
            $upcomingQuery->where('event_date', '>', $endOfDay);

            $pastQuery = clone $baseQuery;
            $pastQuery->where('event_date', '<', $today);

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
            $todayEvents = $todayQuery->with('documents')->orderBy('event_date', 'asc')->paginate(9, ['*'], 'today_page');
            $upcomingEvents = $upcomingQuery->with('documents')->orderBy('event_date', 'asc')->paginate(9, ['*'], 'upcoming_page');
            $pastEvents = $pastQuery->with('documents')->orderBy('event_date', 'desc')->paginate(9, ['*'], 'past_page');

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
            Log::error('ClubController events error: ' . $e->getMessage(), [
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

    public function people(Club $club, Request $request)
    {
        $baseQuery = $club->members()
            ->with(['section.schoolClass'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('class'), function ($query) use ($request) {
                $query->whereHas('section', function ($q) use ($request) {
                    $q->where('class_id', $request->input('class'));
                });
            })
            ->when($request->filled('section'), function ($query) use ($request) {
                $query->where('section_id', $request->input('section'));
            });

        // Get all members for analytics (for club adviser only)
        $allMembers = collect();
        if (Auth::id() === $club->club_adviser) {
            $allMembers = $club->members()->with(['section.schoolClass'])->get();
        }

        // Get paginated members for display
        $members = $baseQuery->paginate($request->input('per_page', 20));

        $classes = SchoolClass::all();
        $sections = Section::when($request->filled('class'), function ($query) use ($request) {
            $query->where('class_id', $request->input('class'));
        })->get();

        // Get pending join requests for club adviser
        $joinRequests = collect();
        if (Auth::id() === $club->club_adviser) {
            $joinRequests = $club->joinRequests()
                ->with(['user.section.schoolClass'])
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('clubs.people.index', compact('club', 'members', 'allMembers', 'classes', 'sections', 'joinRequests'));
    }

    public function about(Club $club)
    {
        return view('clubs.about.index', [
            'club' => $club->load('adviser')
        ]);
    }

    public function checkClubNameExists(Request $request)
    {
        $name = $request->input('value');
        $excludeId = $request->input('exclude');

        $query = Club::where('club_name', $name);

        // If we're excluding a club (for edit validation), add the condition
        if ($excludeId) {
            $query->where('club_id', '!=', $excludeId);
        }

        $exists = $query->exists();

        return response()->json(['exists' => $exists]);
    }

    public function removeMember(Request $request, Club $club, User $user)
    {
        abort_if(auth()->id() !== $club->club_adviser, 403);

        try {
            // Detach the member from the club
            // This will remove the entry from the pivot table (tbl_club_membership)
            $club->members()->detach($user->user_id);

            return response()->json(['message' => 'Member removed successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error removing member: ' . $e->getMessage()], 500);
        }
    }

    public function removeBulkMembers(Request $request, Club $club)
    {
        abort_if(auth()->id() !== $club->club_adviser, 403);

        $request->validate([
            'member_ids' => 'required|array',
            'member_ids.*' => 'exists:tbl_users,user_id'
        ]);

        try {
            // Detach the members from the club
            $club->members()->detach($request->member_ids);

            $count = count($request->member_ids);
            $message = $count === 1
                ? 'Member removed successfully'
                : "{$count} members removed successfully";

            return response()->json(['message' => $message], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error removing members: ' . $e->getMessage()], 500);
        }
    }

    public function approveJoinRequest(Request $request, Club $club, ClubJoinRequest $joinRequest)
    {
        abort_if(auth()->id() !== $club->club_adviser, 403);

        try {
            // Update request status
            $joinRequest->update(['status' => 'approved']);

            // Add user as club member
            $club->members()->attach($joinRequest->user_id, [
                'club_role' => 'MEMBER',
                'joined_date' => now(),
                'club_accessibility' => null
            ]);

            return response()->json(['message' => 'Join request approved successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error approving request: ' . $e->getMessage()], 500);
        }
    }

    public function rejectJoinRequest(Request $request, Club $club, ClubJoinRequest $joinRequest)
    {
        abort_if(auth()->id() !== $club->club_adviser, 403);

        try {
            // Update request status
            $joinRequest->update(['status' => 'rejected']);

            return response()->json(['message' => 'Join request rejected successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error rejecting request: ' . $e->getMessage()], 500);
        }
    }

    public function toggleApprovalRequirement(Request $request, Club $club)
    {
        abort_if(auth()->id() !== $club->club_adviser, 403);

        try {
            $club->update(['requires_approval' => !$club->requires_approval]);

            return response()->json([
                'message' => 'Approval requirement updated successfully',
                'requires_approval' => $club->requires_approval
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating approval requirement: ' . $e->getMessage()], 500);
        }
    }

    public function showMemberProfile(Club $club, User $user)
    {
        // Ensure only club adviser can view member profiles
        abort_if(Auth::id() !== $club->club_adviser, 403);

        // Verify that the user is actually a member of this club
        abort_if(!$club->members()->where('tbl_club_membership.user_id', $user->user_id)->exists(), 404);

        // Load user with all necessary relationships for the profile
        $student = User::with([
            'section.schoolClass',
            'clubMemberships.club',
            'clubJoinRequests.club'
        ])->findOrFail($user->user_id);

        // Get the student's membership details for this specific club
        $membership = $club->members()
            ->where('tbl_club_membership.user_id', $user->user_id)
            ->withPivot(['club_role', 'club_position', 'created_at'])
            ->first();

        return view('clubs.members.profile', compact('club', 'student', 'membership'));
    }

    public function updateMemberStatus(Request $request, Club $club, User $user)
    {
        // Check if user is club adviser or officer
        $currentUser = auth()->user();
        $isAdviser = $currentUser->user_id === $club->club_adviser;
        $membershipRecord = $currentUser->joinedClubs()->where('tbl_club_membership.club_id', $club->club_id)->first();
        $isOfficer = $membershipRecord && $membershipRecord->pivot->club_role === 'Officer';

        if (!$isAdviser && !$isOfficer) {
            abort(403, 'Only club advisers and officers can update member status.');
        }

        $validated = $request->validate([
            'status' => 'required|in:ACTIVE,INACTIVE'
        ]);

        $user->update(['status' => $validated['status']]);

        return back()->with('success', 'Member status updated successfully!');
    }
}
