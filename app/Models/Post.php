<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'tbl_posts';
    protected $primaryKey = 'post_id';
    public $timestamps = true;

    protected $fillable = [
        'post_caption',
        'file_attachment',
        'file_original_name',
        'file_mime_type',
        'file_size',
        'club_id',
        'author_id',
        'post_visibility',
        'post_date',
    ];

    protected $casts = [
        'post_date' => 'datetime',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class, 'club_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function images()
    {
        return $this->hasMany(PostImage::class, 'post_id');
    }

    /**
     * Get all documents for this post.
     */
    public function documents()
    {
        return $this->hasMany(PostDocument::class, 'post_id', 'post_id');
    }

    /**
     * Determine if a user can view this post.
     *
     * @param  \App\Models\User|null  $user
     * @return bool
     */
    public function canBeViewedBy($user)
    {
        // PUBLIC posts can be viewed by anyone
        if ($this->post_visibility === 'PUBLIC') {
            return true;
        }

        // For CLUB_ONLY posts, check permissions
        // User must be logged in and either the author, club adviser, or club member
        if (
            $user &&
            ($user->user_id === $this->author_id ||
                $user->user_id === $this->club->club_adviser ||
                $this->club->members()->where('tbl_club_membership.user_id', $user->user_id)->exists())
        ) {
            return true;
        }

        return false;
    }
}
