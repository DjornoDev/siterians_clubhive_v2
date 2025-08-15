<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClubQuestionAnswer extends Model
{
    protected $fillable = [
        'club_join_request_id',
        'club_question_id',
        'user_id',
        'answer'
    ];

    public function clubJoinRequest(): BelongsTo
    {
        return $this->belongsTo(ClubJoinRequest::class);
    }

    public function clubQuestion(): BelongsTo
    {
        return $this->belongsTo(ClubQuestion::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
