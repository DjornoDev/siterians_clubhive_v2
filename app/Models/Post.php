<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'tbl_posts';
    protected $primaryKey = 'post_id';

    public $timestamps = true;

    protected $fillable = [
        'post_caption',
        'club_id',
        'author_id',
        'post_visibility',
        'post_date',
    ];

    protected $casts = [
        'post_date' => 'datetime',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class, 'club_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function images()
    {
        return $this->hasMany(PostImage::class, 'post_id');
    }
}
