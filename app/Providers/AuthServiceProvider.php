<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Club;
use App\Policies\ClubPolicy;
use Illuminate\Support\Facades\Gate;
use App\Models\Event;
use App\Policies\EventPolicy;
use App\Models\Post;
use App\Policies\PostPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Event::class => EventPolicy::class,
        Post::class => PostPolicy::class,
    ];

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
        // Define club access gate
        // In AuthServiceProvider.php's boot() method
        Gate::define('club-access', function ($user, $club) {
            return $user->role === 'TEACHER'
                ? $club->club_adviser === $user->user_id
                : $club->members()->where('tbl_club_membership.user_id', $user->user_id)->exists();
        });
    }
}
