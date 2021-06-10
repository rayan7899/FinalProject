<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'national_id',
        'name',
        'phone',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function manager()
    {
        return $this->hasOne(Manager::class);
    }
    
    public function trainer()
    {
        return $this->hasOne(Trainer::class);
    }

    public function hasRole($role)
    {
        $user = $this;
        if ($user->manager !== null) {
            return $user->manager->hasRole($role);
        } else {
            if ($role == "متدرب") {
                if ($user->student !== null) {
                    return true;
                }
            }
        }
        return false;
    }

    public function isDepartmentManager()
    {
        $user = $this;
        if ($user->manager !== null) {
            return $user->manager->isDepartmentManager();
        }
        return false;
    }
}
