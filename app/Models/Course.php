<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function studentCourse()
    {
        return $this->belongsToMany(StudentCourse::class);
    }

    public function major()
    {
        return $this->belongsTo(Major::class);
    }
}
