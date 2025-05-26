<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Post;
use Illuminate\Http\Request;

class HomeUpdateController extends Controller
{    public function checkPostChanges(Request $request)
    {
        $currentChecksum = $request->query('checksum', '');
        $userId = auth()->id();
        $clubsUserIsMemberOf = [];
        
        if (auth()->check()) {
            // Get all clubs the user is associated with (as member or adviser)
            $clubsUserIsMemberOf = auth()->user()->getAllAssociatedClubIds();
        }
        
        // Get all public posts and CLUB_ONLY posts for clubs the user is a member of
        $posts = Post::where(function ($query) use ($userId, $clubsUserIsMemberOf) {
                // Get all PUBLIC posts
                $query->where('post_visibility', 'PUBLIC');
                
                // Add CLUB_ONLY posts where the user is a member or is the author
                if (auth()->check()) {
                    $query->orWhere(function ($q) use ($userId, $clubsUserIsMemberOf) {
                        $q->where('post_visibility', 'CLUB_ONLY')
                          ->where(function($innerQuery) use ($userId, $clubsUserIsMemberOf) {
                              // Post author can see their own posts
                              $innerQuery->where('author_id', $userId)
                                        // Club members can see posts from their clubs
                                        ->orWhereIn('club_id', $clubsUserIsMemberOf);
                          });
                    });
                }
            })
            ->latest()
            ->get();
        
        // Generate a new checksum that includes post IDs and updated timestamps
        // This will change if posts are added, edited, or deleted
        $newChecksum = md5(json_encode($posts->pluck('post_id')->merge($posts->pluck('updated_at'))));
        
        // Compare the checksums to determine if there are any changes
        $hasChanges = $currentChecksum !== $newChecksum;
            
        return response()->json([
            'hasChanges' => $hasChanges
        ]);
    }    public function checkEventChanges(Request $request)
    {
        $currentChecksum = $request->query('checksum', '');
        $userId = auth()->id();
        $clubsUserIsMemberOf = [];
        
        if (auth()->check()) {
            // Get all clubs the user is associated with (as member or adviser)
            $clubsUserIsMemberOf = auth()->user()->getAllAssociatedClubIds();
        }
        
        // Get today's events (both PUBLIC and CLUB_ONLY where user is a member)
        $todayEvents = Event::where(function($query) use ($userId, $clubsUserIsMemberOf) {
                // Get all PUBLIC events
                $query->where('event_visibility', 'PUBLIC');
                
                // Add CLUB_ONLY events where the user is a member or is the organizer
                if (auth()->check()) {
                    $query->orWhere(function ($q) use ($userId, $clubsUserIsMemberOf) {
                        $q->where('event_visibility', 'CLUB_ONLY')
                          ->where(function($innerQuery) use ($userId, $clubsUserIsMemberOf) {
                              // Event organizer can see their own events
                              $innerQuery->where('organizer_id', $userId)
                                        // Club members can see events from their clubs
                                        ->orWhereIn('club_id', $clubsUserIsMemberOf);
                          });
                    });
                }
            })
            ->whereDate('event_date', today())
            ->get();
            
        // Get upcoming events (both PUBLIC and CLUB_ONLY where user is a member)
        $upcomingEvents = Event::where(function($query) use ($userId, $clubsUserIsMemberOf) {
                // Get all PUBLIC events
                $query->where('event_visibility', 'PUBLIC');
                
                // Add CLUB_ONLY events where the user is a member or is the organizer
                if (auth()->check()) {
                    $query->orWhere(function ($q) use ($userId, $clubsUserIsMemberOf) {
                        $q->where('event_visibility', 'CLUB_ONLY')
                          ->where(function($innerQuery) use ($userId, $clubsUserIsMemberOf) {
                              // Event organizer can see their own events
                              $innerQuery->where('organizer_id', $userId)
                                        // Club members can see events from their clubs
                                        ->orWhereIn('club_id', $clubsUserIsMemberOf);
                          });
                    });
                }
            })
            ->where('event_date', '>', today())
            ->orderBy('event_date')
            ->take(5)
            ->get();
            
        // Combine events for the checksum
        $events = $todayEvents->merge($upcomingEvents);
        
        // Generate a new checksum that includes event IDs and updated timestamps
        $newChecksum = md5(json_encode($events->pluck('event_id')->merge($events->pluck('updated_at'))));
        
        // Compare the checksums to determine if there are any changes
        $hasChanges = $currentChecksum !== $newChecksum;
            
        return response()->json([
            'hasChanges' => $hasChanges
        ]);
    }
}
