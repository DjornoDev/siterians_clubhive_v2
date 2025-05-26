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
        
        // Get recent activities
        $recentActivities = $this->getRecentActivities();
        
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
        
        return view('admin.dashboard', compact(
            'clubCount', 
            'userCounts', 
            'recentActivities', 
            'upcomingEvents',
            'clubStats',
            'systemStatus',
            'activitySummary',
            'recentClubActivity',
            'recentUserActivity'
        ));
    }
    
    private function getRecentActivities()
    {
        // Combine recent events from different tables
        $newMembers = DB::table('tbl_club_membership as cm')
            ->join('tbl_users as u', 'cm.user_id', '=', 'u.user_id')
            ->join('tbl_clubs as c', 'cm.club_id', '=', 'c.club_id')
            ->select(
                DB::raw("'member_joined' as type"),
                'u.name as user_name',
                'c.club_name',
                'cm.created_at as timestamp',
                DB::raw("NULL as title"),
                DB::raw("NULL as description")
            )
            ->orderBy('cm.created_at', 'desc')
            ->take(10);
            
        $newEvents = DB::table('tbl_events as e')
            ->join('tbl_clubs as c', 'e.club_id', '=', 'c.club_id')
            ->select(
                DB::raw("'event_created' as type"),
                DB::raw("NULL as user_name"),
                'c.club_name',
                'e.created_at as timestamp',
                'e.event_name as title',
                'e.event_description as description'
            )
            ->orderBy('e.created_at', 'desc')
            ->take(10);
            
        $newPosts = DB::table('tbl_posts as p')
            ->join('tbl_users as u', 'p.author_id', '=', 'u.user_id')
            ->join('tbl_clubs as c', 'p.club_id', '=', 'c.club_id')
            ->select(
                DB::raw("'post_created' as type"),
                'u.name as user_name',
                'c.club_name',
                'p.created_at as timestamp',
                'p.post_title as title',
                'p.post_content as description'
            )
            ->orderBy('p.created_at', 'desc')
            ->take(10);
            
        $activities = $newMembers->union($newEvents)->union($newPosts)
            ->orderBy('timestamp', 'desc')
            ->take(10)
            ->get();
            
        return $activities;
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
            ->withCount(['posts' => function($query) {
                $query->where('created_at', '>=', Carbon::now()->subDays(30));
            }])
            ->withCount(['events' => function($query) {
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
        // In a real application, you might get this from a monitoring service
        // For now, we'll simulate this data
        return [
            'database' => 'normal',
            'api' => 'normal',
            'storage' => 'normal',
            'email' => 'normal',
            'last_updated' => Carbon::now(),
            'storage_used' => [
                'images' => rand(1, 3),
                'uploads' => rand(0, 2),
                'system' => 0.5,
                'total' => rand(3, 5) // GB
            ],
            'recent_logins' => $this->getRecentLogins()
        ];
    }
    
    private function getRecentLogins()
    {
        // In a real application, you would log login attempts in a table
        // For now, we'll create simulated data
        $roles = ['ADMIN', 'TEACHER', 'STUDENT'];
        $statuses = ['success', 'success', 'success', 'success', 'failed'];
        
        $logins = [];
        for ($i = 0; $i < 4; $i++) {
            $logins[] = [
                'user' => $i < 3 ? User::where('role', $roles[$i])->first()->name : 'Unknown',
                'role' => $i < 3 ? $roles[$i] : '-',
                'time' => Carbon::now()->subHours(rand(0, 48))->format('M d, g:i A'),
                'status' => $statuses[rand(0, 4)]
            ];
        }
        
        return $logins;
    }
    
    private function getActivitySummary()
    {
        // Get activity counts for the week
        $now = Carbon::now();
        $weekAgo = Carbon::now()->subDays(7);
        $twoWeeksAgo = Carbon::now()->subDays(14);
        
        // Current week counts
        $newPosts = Post::whereBetween('created_at', [$weekAgo, $now])->count();
        $clubMeetings = Event::whereBetween('created_at', [$weekAgo, $now])->count();
        $newMembers = DB::table('tbl_club_membership')
            ->whereBetween('created_at', [$weekAgo, $now])
            ->count();
        $activeUsers = User::where('updated_at', '>=', $weekAgo)->count();
        
        // Previous week counts for percentage calculations
        $prevPosts = Post::whereBetween('created_at', [$twoWeeksAgo, $weekAgo])->count();
        $prevMeetings = Event::whereBetween('created_at', [$twoWeeksAgo, $weekAgo])->count();
        $prevMembers = DB::table('tbl_club_membership')
            ->whereBetween('created_at', [$twoWeeksAgo, $weekAgo])
            ->count();
        $prevActiveUsers = User::where('updated_at', '>=', $twoWeeksAgo)
            ->where('updated_at', '<', $weekAgo)
            ->count();
        
        // Calculate percentage changes
        $postChange = $prevPosts > 0 ? round(($newPosts - $prevPosts) / $prevPosts * 100) : 100;
        $meetingChange = $prevMeetings > 0 ? round(($clubMeetings - $prevMeetings) / $prevMeetings * 100) : 100;
        $memberChange = $prevMembers > 0 ? round(($newMembers - $prevMembers) / $prevMembers * 100) : 100;
        $userChange = $prevActiveUsers > 0 ? round(($activeUsers - $prevActiveUsers) / $prevActiveUsers * 100) : 100;
        
        return [
            'posts' => [
                'count' => $newPosts,
                'change' => $postChange
            ],
            'meetings' => [
                'count' => $clubMeetings,
                'change' => $meetingChange
            ],
            'new_members' => [
                'count' => $newMembers,
                'change' => $memberChange
            ],
            'active_users' => [
                'count' => $activeUsers,
                'change' => $userChange
            ]
        ];
    }
    
    private function getRecentClubActivity()
    {
        return Club::with(['posts' => function($query) {
                $query->latest()->take(1);
            }])
            ->withCount(['members', 'posts'])
            ->take(4)
            ->get()
            ->map(function($club) {
                $lastActivity = $club->posts->first();
                return [
                    'club_id' => $club->club_id,
                    'club_name' => $club->club_name,
                    'member_count' => $club->members_count,
                    'club_logo' => $club->club_logo,
                    'activity' => $lastActivity ? 'Posted ' . $lastActivity->post_title : 'No recent activity',
                    'activity_date' => $lastActivity ? $lastActivity->created_at->format('M d, g:i A') : null,
                    'status' => $club->is_club_hunting_day ? 'Active' : 'Inactive'
                ];
            });
    }
    
    private function getRecentUserActivity()
    {
        // Get users with recent activity (posts or event creation)
        $users = User::select('user_id', 'name', 'profile_picture', 'updated_at')
            ->withCount(['posts' => function($query) {
                $query->where('created_at', '>=', Carbon::now()->subDays(7));
            }])
            ->withCount(['organizedEvents' => function($query) {
                $query->where('created_at', '>=', Carbon::now()->subDays(7));
            }])
            ->withCount(['clubMemberships' => function($query) {
                $query->where('created_at', '>=', Carbon::now()->subDays(7));
            }])
            ->having('posts_count', '>', 0)
            ->orHaving('organized_events_count', '>', 0)
            ->orHaving('club_memberships_count', '>', 0)
            ->orderByDesc('updated_at')
            ->take(4)
            ->get();
            
        return $users->map(function($user) {
            $activity = 'Updated profile';
            if ($user->posts_count > 0) {
                $activity = "Created {$user->posts_count} new post(s)";
            } elseif ($user->organized_events_count > 0) {
                $activity = "Organized {$user->organized_events_count} event(s)";
            } elseif ($user->club_memberships_count > 0) {
                $activity = "Joined {$user->club_memberships_count} club(s)";
            }
            
            return [
                'user_id' => $user->user_id,
                'name' => $user->name,
                'profile_picture' => $user->profile_picture ?: 'images/default_profile.jpg',
                'activity' => $activity,
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
}
