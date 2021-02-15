<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    public function majors()
    {
        return $this->hasMany(Major::class);
    }
    
    public function program(){
        return $this->belongsTo(Program::class);
    }
}
