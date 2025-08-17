<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Club;
use App\Models\Event;

class ClubViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share pending join requests count with club navigation views
        View::composer('clubs.layouts.navigation', function ($view) {
            $club = $view->getData()['club'] ?? null;

            if ($club instanceof Club && Auth::check() && Auth::user()->user_id === $club->club_adviser) {
                $pendingRequestsCount = $club->joinRequests()
                    ->where('status', 'pending')
                    ->count();

                $view->with('pendingRequestsCount', $pendingRequestsCount);
            } else {
                $view->with('pendingRequestsCount', 0);
            }
        });

        // Share pending events count with dashboard layout for SSLG adviser
        View::composer('layouts.dashboard', function ($view) {
            $pendingEventsCount = 0;

            if (Auth::check()) {
                // Check if user is SSLG adviser (club ID 1)
                $sslgClub = Club::find(1);
                if ($sslgClub && Auth::id() === $sslgClub->club_adviser) {
                    $pendingEventsCount = \App\Models\Event::where('approval_status', 'pending')->count();
                }
            }

            $view->with('pendingEventsCount', $pendingEventsCount);
        });
    }
}
