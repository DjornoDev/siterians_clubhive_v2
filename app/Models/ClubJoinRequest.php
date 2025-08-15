<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubJoinRequest extends Model
{
    use HasFactory;

    protected $table = 'tbl_club_join_requests';
    protected $primaryKey = 'request_id';

    protected $fillable = [
        'club_id',
        'user_id',
        'status',
        'message'
    ];

    public function club()
    {
        return $this->belongsTo(Club::class, 'club_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function questionAnswers()
    {
        return $this->hasMany(ClubQuestionAnswer::class, 'club_join_request_id');
    }
}
