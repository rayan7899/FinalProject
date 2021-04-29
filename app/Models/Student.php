<?php

namespace App\Models;

use App\Http\Controllers\UserController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    // protected $fillable = [
    //     'user_id',
    //     'rayat_id',
    //     'birthdate',
    //     'program_id',
    //     'department_id',
    //     'major_id',
    //     'final_accepted',
    //     'documents_verified',
    //     'agreement',
    //     'traineeState',
    //     'data_updated',
    //     'wallet',
    //     'note'
    // ];
    protected $guarded = [];



    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function major()
    {
        return $this->belongsTo(Major::class);
    }

    // public function studentCourses()
    // {
    //     return $this->hasMany(StudentCourse::class);
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, "student_courses");
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function refunds()
    {
        return $this->hasMany(RefundOrder::class);
    }

    public function departmentRoleId()
    {
        return $this->department->role_id;
    }
}
