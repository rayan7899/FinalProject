<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function majors()
    {
        return $this->hasMany(Major::class);
    }
    
    public function program(){
        return $this->belongsTo(Program::class);
    }

        
    static function findDepartment($programs, $program_id) {
    
        for ($i = 0; $i < count($programs); $i++) {
            if ($programs[$i]->id == $program_id) {
                return $programs[$i]->departments;
            }
        }
    }
}
