<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerCoursesOrders extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}
