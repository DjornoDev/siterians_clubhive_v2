<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $table = 'tbl_sections';
    protected $primaryKey = 'section_id';

    protected $fillable = [
        'class_id',
        'section_name',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'section_id');
    }
}
