<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Hashids\Hashids;
use App\Models\Club;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Hashids::class, function () {
            return new Hashids(
                env('HASHIDS_SALT', 'your-secret-salt'),
                env('HASHIDS_LENGTH', 16),
                env('HASHIDS_ALPHABET', 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890')
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        config(['app.timezone' => 'Asia/Manila']); // Set your school's timezone

        Route::bind('club', function ($value) {
            // First try to decode as hashid
            $decoded = app(Hashids::class)->decode($value);

            if (!empty($decoded)) {
                return Club::findOrFail($decoded[0]);
            }

            // If that fails, try as numeric ID (for existing URLs)
            return Club::findOrFail($value);
        });

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
