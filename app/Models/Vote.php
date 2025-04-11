<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $table = 'tbl_votes';
    protected $primaryKey = 'vote_id';

    protected $fillable = [
        'election_id',
        'candidate_id',
        'voter_id',
    ];

    public function election()
    {
        return $this->belongsTo(Election::class, 'election_id');
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }

    public function voter()
    {
        return $this->belongsTo(User::class, 'voter_id');
    }
}
