<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\User;
use App\Models\Event;
use App\Models\ClubMembership;
use App\Models\Post;

class ClubController extends Controller
{
    public function index()
    {
        $clubs = Club::with(['adviser', 'members' => function ($query) {
            $query->where('tbl_club_membership.user_id', auth()->id()); // Add table prefix
        }])->get();

        return view('clubs.index-all', compact('clubs'));
    }

    public function join(Club $club)
    {
        $user = auth()->user();

        // Add table prefix to club_id
        if ($user->joinedClubs()->where('tbl_club_membership.club_id', $club->club_id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'You are already a member of this club!'
            ], 409);
        }

        // Rest of your code remains the same
        $user->joinedClubs()->attach($club->club_id, [
            'club_role' => 'MEMBER',
            'joined_date' => now(),
            'club_accessibility' => null
        ]);

        return response()->json(['success' => true]);
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
                'club_description' => 'nullable|string',
                'club_logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
                'club_banner' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB
            ]);

            $logoPath = $request->file('club_logo')->store('club-logos', 'public');
            $bannerPath = $request->file('club_banner')->store('club-banners', 'public');

            Club::create([
                'club_name' => $validated['club_name'],
                'club_adviser' => $validated['club_adviser'],
                'club_description' => $validated['club_description'],
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
                'club_description' => 'nullable|string',
                'club_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'club_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
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

    public function show(Club $club)
    {
        return view('clubs.index', [
            'club' => $club->loadCount('members'),
            'posts' => $club->posts()->latest()->paginate(10)
        ]);
    }

    public function events(Club $club)
    {
        return view('clubs.events.index', [
            'club' => $club,
            'events' => $club->events()->latest()->paginate(10)
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

        return view('clubs.people.index', compact('club', 'members', 'classes', 'sections'));
    }

    public function voting(Club $club)
    {
        return view('clubs.voting.index', compact('club'));
    }

    public function about(Club $club)
    {
        return view('clubs.about.index', [
            'club' => $club->load('adviser')
        ]);
    }
}
