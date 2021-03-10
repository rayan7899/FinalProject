<?php

namespace App\Models;

use App\Http\Controllers\UserController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'birthdate',
        'program_id',
        'department_id',
        'major_id',
        'documents_verified',
        'agreement',
        'traineeState', 
        'data_updated',
        'wallet'
    ];



    public function program(){
        return $this->belongsTo(Program::class);
    }

    public function department(){
        return $this->belongsTo(Department::class);
    }

    public function major(){
        return $this->belongsTo(Major::class);
    }

    // public function user(){
    //     return $this->hasOne(User::class);
    // }
}
