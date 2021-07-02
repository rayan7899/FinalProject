<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
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

    public function coursesOrders()
    {
        return $this->hasMany(TrainerCoursesOrders::class);
    }

    public function hasRejectedOrder()
    {
        return $this->coursesOrders()
            ->Where(function($res){
                $res->where('accepted_by_dept_boss', true)
                    ->Where('accepted_by_community', false);
            })
            ->exists();
    }
}
