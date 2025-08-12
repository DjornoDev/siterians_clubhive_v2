<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
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

            Club::create([
                'club_name' => $validated['club_name'],
                'club_adviser' => $validated['club_adviser'],
                'club_description' => $validated['club_description'],
                'category' => $validated['category'],
                'requires_approval' => $validated['requires_approval'] ?? true,
                'club_logo' => $logoPath,
                'club_banner' => $bannerPath,
            ]);

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

            return redirect()->route('admin.clubs.index')->with('success', 'Club updated successfully');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error updating club: ' . $e->getMessage());
        }
    }

    public function destroy(Club $club)
    {
        try {
            // Delete associated files
            if ($club->club_logo) {
                Storage::disk('public')->delete($club->club_logo);
            }
            if ($club->club_banner) {
                Storage::disk('public')->delete($club->club_banner);
            }

            $club->delete();

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
            ->whereDate('event_date', today())
            ->orderBy('event_time')
            ->get();

        $upcomingEvents = $club->events()
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
            'posts' => $postsQuery->latest()->paginate(10),
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
        $events = $club->events()->get();

        // Generate a new checksum that includes event IDs and updated timestamps
        // This will change if events are added, edited, or deleted
        $newChecksum = md5(json_encode($events->pluck('event_id')->merge($events->pluck('updated_at'))));

        // Compare the checksums to determine if there are any changes
        $hasChanges = $currentChecksum !== $newChecksum;

        return response()->json([
            'hasChanges' => $hasChanges
        ]);
    }

    public function events(Club $club)
    {
        $todayEvents = $club->events()
            ->whereDate('event_date', today())
            ->orderBy('event_time')
            ->get();

        $upcomingEvents = $club->events()
            ->where('event_date', '>', today())
            ->orderBy('event_date')
            ->paginate(10);

        return view('clubs.events.index', [
            'club' => $club,
            'todayEvents' => $todayEvents,
            'upcomingEvents' => $upcomingEvents
        ]);
    }

    public function people(Club $club, Request $request)
    {
        $members = $club->members()
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
            })
            ->paginate($request->input('per_page', 20));

        $classes = SchoolClass::all();
        $sections = Section::when($request->filled('class'), function ($query) use ($request) {
            $query->where('class_id', $request->input('class'));
        })->get();

        // Get pending join requests for club adviser
        $joinRequests = collect();
        if (auth()->user()->user_id === $club->club_adviser) {
            $joinRequests = $club->joinRequests()
                ->with(['user.section.schoolClass'])
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('clubs.people.index', compact('club', 'members', 'classes', 'sections', 'joinRequests'));
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
}
