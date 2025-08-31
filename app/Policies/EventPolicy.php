<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use App\Models\Club;
use App\Models\Post;
use App\Services\MainClubService;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Event $event): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Club $club)
    {
        return $club->members()->where('tbl_club_membership.user_id', $user->user_id)
            ->whereJsonContains('club_accessibility->manage_events', true)
            ->exists() || $user->user_id === $club->club_adviser;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event)
    {
        // Main club adviser (SSLG) can update any event
        if (MainClubService::isMainClubAdviser($user->user_id)) {
            return true;
        }

        return $user->user_id === $event->club->club_adviser ||
            (
                $event->club->members()
                ->where('tbl_club_membership.user_id', $user->user_id)
                ->whereJsonContains('club_accessibility->manage_events', true)
                ->exists()
                && $user->user_id === $event->organizer_id // Add author check
            );
    }
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event)
    {
        return $this->update($user, $event);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Event $event): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Event $event): bool
    {
        return false;
    }
}
