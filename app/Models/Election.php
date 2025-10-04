<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    use HasFactory;

    protected $table = 'tbl_elections';
    protected $primaryKey = 'election_id';

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'club_id',
        'is_published',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function candidates()
    {
        return $this->hasMany(Candidate::class, 'election_id');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class, 'election_id');
    }

    public function club()
    {
        return $this->belongsTo(Club::class, 'club_id');
    }

    /**
     * Check if the election has any candidates
     * 
     * @return bool
     */
    public function hasCandidates()
    {
        return $this->candidates()->count() > 0;
    }

    /**
     * Get the total number of candidates in this election
     * 
     * @return int
     */
    public function getCandidatesCount()
    {
        return $this->candidates()->count();
    }
}
