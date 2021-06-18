<?php

namespace App\Models;

use App\Http\Controllers\UserController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

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

    public function getHourCost($traineeState)
    {
        switch ($traineeState) {
            case "trainee":
                return $this->program->hourPrice;
                break;
            case "privateState":
                return 0;
                break;
            case "employeeSon":
                return $this->program->hourPrice * 0.50;
                break;
            case "employee":
                return $this->program->hourPrice * 0.25;
                break;
            default:
                return false;
        }
    }
}
