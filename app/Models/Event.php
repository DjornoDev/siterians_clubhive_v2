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
        'approval_status',
        'rejection_reason',
        'supporting_documents',
        'supporting_documents_original_name',
        'supporting_documents_mime_type',
        'supporting_documents_size',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_visibility' => 'string',
        'approved_at' => 'datetime',
        'supporting_documents_size' => 'integer',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class, 'club_id');
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Check if event is approved
     */
    public function isApproved()
    {
        return $this->approval_status === 'approved';
    }

    /**
     * Check if event is pending
     */
    public function isPending()
    {
        return $this->approval_status === 'pending';
    }

    /**
     * Check if event is rejected
     */
    public function isRejected()
    {
        return $this->approval_status === 'rejected';
    }

    /**
     * Determine if a user can view this event.
     *
     * @param  \App\Models\User|null  $user
     * @return bool
     */
    public function canBeViewedBy($user)
    {
        // If event is not approved, only organizer and SSLG adviser can view it
        if (!$this->isApproved()) {
            if (!$user) {
                return false;
            }

            // Event organizer can view their own events
            if ($user->user_id === $this->organizer_id) {
                return true;
            }

            // SSLG adviser (club ID 1) can view all pending events
            $sslgClub = Club::find(1);
            if ($sslgClub && $user->user_id === $sslgClub->club_adviser) {
                return true;
            }

            return false;
        }

        // For approved PUBLIC events, anyone can view
        if ($this->event_visibility === 'PUBLIC') {
            return true;
        }

        // For approved CLUB_ONLY events, check permissions
        // User must be logged in and either the organizer, club adviser, or club member
        if (
            $user &&
            ($user->user_id === $this->organizer_id ||
                $user->user_id === $this->club->club_adviser ||
                $this->club->members()->where('tbl_club_membership.user_id', $user->user_id)->exists())
        ) {
            return true;
        }

        return false;
    }
}
