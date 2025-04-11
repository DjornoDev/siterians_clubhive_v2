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
}
