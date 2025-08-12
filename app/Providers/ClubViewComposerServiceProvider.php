<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Club;

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
    }
}
