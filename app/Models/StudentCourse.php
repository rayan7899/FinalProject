<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCourse extends Model
{
    use HasFactory;
    protected $fillable = [
        // 'student_id',
        'course_id',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
