<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\User;
use App\Models\Event;
use App\Models\Post;
use App\Models\ClubMembership;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{    public function index()
    {
        // Get total active clubs
        $totalClubs = Club::count();
        
        // Calculate total club members (unique users in club memberships)
        $totalMembers = User::whereHas('joinedClubs')->count();
        
        // Get upcoming events
        $upcomingEvents = Event::where('event_date', '>', now())
            ->where('event_visibility', 'PUBLIC')
            ->orderBy('event_date')
            ->count();
        
        // Get featured clubs with the most members
        $featuredClubs = Club::withCount('members')
            ->orderByDesc('members_count')
            ->take(3)
            ->get();
        
        // Check club hunting day status
        $isHuntingActive = Club::find(1)?->is_club_hunting_day ?? false;
          // Get latest news/announcements
        $latestPosts = Post::with('author')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
              // Get some school statistics
        $totalStudents = User::where('role', 'STUDENT')->count();
        $totalClubAdvisers = User::where('role', 'TEACHER')->count();
        $totalEvents = Event::count();
          return view('welcome', compact(
            'totalClubs',
            'totalMembers',
            'upcomingEvents',
            'featuredClubs',
            'isHuntingActive',
            'latestPosts',
            'totalStudents',
            'totalClubAdvisers',
            'totalEvents'
        ));
    }
}
