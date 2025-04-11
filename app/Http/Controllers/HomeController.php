<?php

namespace App\Http\Controllers;

use App\Models\{Club, Event, Post};
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home.index', [
            'featuredClubs' => Club::withCount('members')->latest()->take(5)->get(),
            'recentEvents' => Event::with('club')->latest()->take(5)->get(),
            'latestPosts' => Post::with(['club', 'author'])->latest()->paginate(10)
        ]);
    }
}
