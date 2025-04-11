<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $table = 'tbl_classes';
    protected $primaryKey = 'class_id';

    protected $fillable = [
        'grade_level',
    ];

    public function sections()
    {
        return $this->hasMany(Section::class, 'class_id');
    }
}
