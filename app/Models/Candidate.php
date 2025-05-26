<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $table = 'tbl_candidates';
    protected $primaryKey = 'candidate_id';

    protected $fillable = [
        'election_id',
        'user_id',
        'position',
        'partylist',
    ];

    public function election()
    {
        return $this->belongsTo(Election::class, 'election_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class, 'candidate_id');
    }
}