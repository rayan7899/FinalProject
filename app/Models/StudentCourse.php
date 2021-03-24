<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCourse extends Model
{
    protected $fillable = [
        // 'student_id'
        'course_id'
    ];


    use HasFactory;
    protected $fillable = [
        // 'student_id',
        'course_id',
    ];

    public function courses()
    {
        // return $this->hasMany(Course::class);
        // return $this->hasManyThrough(Course::class, Student::class);
        return $this->hasMany(Course::class);
    }

    public function student()
    {
        return $this->belongsToMany(Student::class);
    }
}
