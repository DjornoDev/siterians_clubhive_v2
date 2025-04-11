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
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function candidates()
    {
        return $this->hasMany(Candidate::class, 'election_id');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class, 'election_id');
    }
}
