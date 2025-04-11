<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ClubMembership extends Pivot
{
    protected $table = 'tbl_club_membership';
    protected $primaryKey = 'membership_id';

    protected $fillable = [
        'club_id',
        'user_id',
        'club_role',
        'club_position',
        'joined_date',
        'club_accessibility',
    ];

    protected $casts = [
        'joined_date' => 'datetime',
        'club_accessibility' => 'array',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class, 'club_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
