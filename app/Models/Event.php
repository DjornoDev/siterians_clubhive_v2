<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'tbl_events';
    protected $primaryKey = 'event_id';

    protected $fillable = [
        'event_name',
        'event_description',
        'club_id',
        'organizer_id',
        'event_date',
        'event_time',
        'event_visibility',
        'event_location',
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_visibility' => 'string',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class, 'club_id');
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }
    
    /**
     * Determine if a user can view this event.
     *
     * @param  \App\Models\User|null  $user
     * @return bool
     */
    public function canBeViewedBy($user)
    {
        // PUBLIC events can be viewed by anyone
        if ($this->event_visibility === 'PUBLIC') {
            return true;
        }
        
        // For CLUB_ONLY events, check permissions
        // User must be logged in and either the organizer, club adviser, or club member
        if ($user &&
            ($user->user_id === $this->organizer_id ||
             $user->user_id === $this->club->club_adviser ||
             $this->club->members()->where('tbl_club_membership.user_id', $user->user_id)->exists())) {
            return true;
        }
        
        return false;
    }
}
