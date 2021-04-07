<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    use HasFactory;

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function hasRole($role)
    {
        foreach ($this->permissions as $permission) {
            if ($permission->role->name == $role) {
                return true;
            }
        }
        return false;
        // $role_row = Role::where('name', $role);
        // return $this->permissions()->where('manager_id', $this->id)->Where('role_id', $role_row->id);
    }
}
