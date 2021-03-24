<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    public function studentCourse()
    {
        return $this->belongsToMany(StudentCourse::class);
    }

    public function majors()
    {
        return $this->hasMany(Major::class);
    }
}
