<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Event;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        // Count clubs
        $clubCount = Club::count();

        // Count users by role
        $userCounts = User::select('role', DB::raw('count(*) as count'))
            ->groupBy('role')
            ->pluck('count', 'role')
            ->toArray();



        // Get upcoming events
        $upcomingEvents = Event::with(['club', 'organizer'])
            ->where('event_date', '>=', Carbon::today())
            ->orderBy('event_date')
            ->take(5)
            ->get();

        // Get club statistics
        $clubStats = $this->getClubStatistics();

        // Get system status
        $systemStatus = $this->getSystemStatus();

        // Get activity summary for the week
        $activitySummary = $this->getActivitySummary();

        // Get recent club activity
        $recentClubActivity = $this->getRecentClubActivity();

        // Get recent user activity
        $recentUserActivity = $this->getRecentUserActivity();

        // Get additional variables required by the dashboard
        $clubHuntingDay = false; // Default to false, can be updated based on your logic
        $clubTrend = 0; // Default to 0, can be calculated based on monthly growth

        // Event statistics
        $eventStatistics = [
            'total' => Event::count(),
            'upcoming' => Event::where('event_date', '>', now())->count(),
        ];

        // Get monthly events data for the chart
        $monthlyEventsData = [
            'months' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'counts' => array_fill(0, 12, 0)
        ];

        try {
            $monthlyEventsData = $this->getMonthlyEventsData();
        } catch (\Exception $e) {
            // Keep the fallback data if there's an error
        }

        // Post statistics
        $postStatistics = [
            'total' => Post::count(),
        ];

        // User trends (simplified for now)
        $userTrends = [
            'STUDENT' => 0,
            'TEACHER' => 0,
            'ADMIN' => 0,
        ];

        return view('admin.dashboard', compact(
            'clubCount',
            'userCounts',
            'upcomingEvents',
            'clubStats',
            'systemStatus',
            'activitySummary',
            'recentClubActivity',
            'recentUserActivity',
            'clubHuntingDay',
            'clubTrend',
            'eventStatistics',
            'postStatistics',
            'userTrends',
            'monthlyEventsData'
        ));
    }



    private function getClubStatistics()
    {
        // Get clubs with member counts
        $clubStats = Club::select('club_id', 'club_name')
            ->withCount('members')
            ->orderByDesc('members_count')
            ->take(5)
            ->get();

        // Get recent club activities
        $clubActivities = Club::select('club_id', 'club_name')
            ->withCount(['posts' => function ($query) {
                $query->where('created_at', '>=', Carbon::now()->subDays(30));
            }])
            ->withCount(['events' => function ($query) {
                $query->where('created_at', '>=', Carbon::now()->subDays(30));
            }])
            ->orderByDesc('posts_count')
            ->orderByDesc('events_count')
            ->take(3)
            ->get();

        return [
            'memberCounts' => $clubStats,
            'recentActivities' => $clubActivities
        ];
    }

    private function getSystemStatus()
    {
        return [
            'database' => 'normal',
            'api' => 'normal',
            'storage' => 'normal',
            'email' => 'normal',
            'last_updated' => Carbon::now(),
            'recent_logins' => $this->getRecentLogins()
        ];
    }

    private function getRecentLogins()
    {
        // Get recent user logins (placeholder for future implementation)
        return [];
    }

    private function getActivitySummary()
    {
        // Get activity counts for the week
        $now = Carbon::now();
        $weekAgo = Carbon::now()->subDays(7);

        return [
            'posts' => Post::whereBetween('created_at', [$weekAgo, $now])->count(),
            'meetings' => Event::whereBetween('created_at', [$weekAgo, $now])->count(),
            'new_members' => DB::table('tbl_club_membership')->whereBetween('created_at', [$weekAgo, $now])->count(),
            'active_users' => User::where('updated_at', '>=', $weekAgo)->count()
        ];
    }

    private function getRecentClubActivity()
    {
        return Club::withCount(['members', 'posts'])
            ->take(4)
            ->get()
            ->map(function ($club) {
                return [
                    'club_id' => $club->club_id,
                    'club_name' => $club->club_name,
                    'member_count' => $club->members_count,
                    'club_logo' => $club->club_logo,
                    'status' => $club->is_club_hunting_day ? 'Active' : 'Inactive'
                ];
            });
    }

    private function getRecentUserActivity()
    {
        return User::select('user_id', 'name', 'profile_picture', 'updated_at')
            ->orderByDesc('updated_at')
            ->take(4)
            ->get()
            ->map(function ($user) {
                return [
                    'user_id' => $user->user_id,
                    'name' => $user->name,
                    'profile_picture' => $user->profile_picture ?: 'images/default_profile.jpg',
                    'time' => $user->updated_at->diffForHumans()
                ];
            });
    }

    // Admin operations
    public function manageUsers()
    {
        $users = User::latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function manageClubs()
    {
        $clubs = Club::with('adviser')->latest()->paginate(15);
        return view('admin.clubs.index', compact('clubs'));
    }

    public function systemSettings()
    {
        return view('admin.settings');
    }

    public function exportReports(Request $request)
    {
        // This would generate reports based on the request parameters
        // For now, we'll redirect back with a success message
        return redirect()->back()->with('success', 'Report generated successfully');
    }

    private function getMonthlyEventsData()
    {
        try {
            $months = [];
            $eventCounts = [];

            // Get data for the last 12 months
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $months[] = $date->format('M Y');

                // Count events created in this month
                $count = Event::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();

                $eventCounts[] = $count;
            }

            return [
                'months' => $months,
                'counts' => $eventCounts
            ];
        } catch (\Exception $e) {
            // Return fallback data if there's an error
            return [
                'months' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                'counts' => array_fill(0, 12, 0)
            ];
        }
    }
}
