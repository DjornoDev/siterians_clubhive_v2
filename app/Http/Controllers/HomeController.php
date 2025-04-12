<?php

namespace App\Http\Controllers;

use App\Models\{Club, Event, Post};
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get all public posts with their relationships
        $publicPosts = Post::with(['club', 'author', 'images'])
            ->where('post_visibility', 'PUBLIC')
            ->latest()
            ->paginate(10);

        // Get today's public events
        $todayEvents = Event::with('club')
            ->where('event_visibility', 'PUBLIC')
            ->whereDate('event_date', today())
            ->orderBy('event_time')
            ->get();

        // Get upcoming public events
        $upcomingEvents = Event::with('club')
            ->where('event_visibility', 'PUBLIC')
            ->where('event_date', '>', today())
            ->orderBy('event_date')
            ->take(5)
            ->get();

        return view('home.index', [
            'publicPosts' => $publicPosts,
            'todayEvents' => $todayEvents,
            'upcomingEvents' => $upcomingEvents,
            'featuredClubs' => Club::withCount('members')->latest()->take(5)->get()
        ]);
    }
}
