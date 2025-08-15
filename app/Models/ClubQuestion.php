<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClubQuestion extends Model
{
    protected $fillable = [
        'club_id',
        'question',
        'question_type',
        'options',
        'is_required',
        'order',
        'is_active'
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(ClubQuestionAnswer::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
