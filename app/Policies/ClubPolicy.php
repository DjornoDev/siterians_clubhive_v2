<?php

namespace App\Policies;

use App\Models\Club;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClubPolicy
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
    public function view(User $user, Club $club): bool
    {
        return $user->user_id === $club->club_adviser ||
            $club->members()->where('tbl_club_membership.user_id', $user->user_id)->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Club $club): bool
    {
        return $user->user_id === $club->club_adviser;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Club $club): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Club $club): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Club $club): bool
    {
        return false;
    }
}
