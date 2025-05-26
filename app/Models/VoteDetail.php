<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoteDetail extends Model
{
    use HasFactory;

    protected $table = 'tbl_vote_details';
    protected $primaryKey = 'vote_detail_id';

    protected $fillable = [
        'vote_id',
        'position',
        'candidate_id',
    ];

    public function vote()
    {
        return $this->belongsTo(Vote::class, 'vote_id');
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }
}