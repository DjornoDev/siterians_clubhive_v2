<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use App\Models\Club;
use Illuminate\Auth\Access\Response;

class PostPolicy
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
    public function view(User $user, Post $post): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Club $club)
    {
        return $club->members()
            ->where('tbl_club_membership.user_id', $user->user_id) // Add table prefix
            ->whereJsonContains('club_accessibility->manage_posts', true)
            ->exists()
            || $user->user_id === $club->club_adviser;
    }
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post)
    {
        return $user->user_id === $post->club->club_adviser ||
            (
                $post->club->members()
                ->where('tbl_club_membership.user_id', $user->user_id)
                ->whereJsonContains('club_accessibility->manage_posts', true)
                ->exists()
                && $user->user_id === $post->author_id // Add author check
            );
    }
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post)
    {
        return $this->update($user, $post);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Post $post): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Post $post): bool
    {
        return false;
    }
}
