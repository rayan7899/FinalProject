<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function department(){
        return $this->belongsTo(Department::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }


    // static function findMajor($programs, $program_id, $department_id) {
    //     for ($i = 0; $i < count($programs) -1; $i++) {
    //         for ($j = 0; $j < count($programs[$i]->departments) - 1; $j++) {
    //             if ($programs[$i]->id == $program_id && $programs[$i]->departments[$j]->id == $department_id) {
    //                 return $programs[$i]->departments[$j];
    //             }
    //         }
    //     }
    // }
}
