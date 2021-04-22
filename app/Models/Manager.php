<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Manager extends Model
{
    use HasFactory;

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function hasRole($role)
    {
        try {
            foreach ($this->permissions as $permission) {
                if ($permission->role->name == $role) {
                    return true;
                }
            }
            return false;
        } catch (Exception $e) {
            Log::error($e);
            return false;
        }

        // $role_row = Role::where('name', $role);
        // return $this->permissions()->where('manager_id', $this->id)->Where('role_id', $role_row->id);
    }

    public function isDepartmentManager()
    {
        foreach ($this->permissions as $permission) {
            $diplomPattern =  ' - دبلوم ';
            $baccPattern = ' - بكالوريوس ';
            $name = $permission->role->name;
            $hasRole = (str_ends_with($name, $diplomPattern) || str_ends_with($name, $baccPattern));
        }
        return $hasRole;
    }

    public function getPermissionRoleIds()
    {
        return $roles = array_map(
            function ($p) {
                return $p['role_id'];
            },
            $this->permissions->toArray()
        );
    }

    public function getMyDepartment()
    {
        try {
            $roles = $this->getPermissionRoleIds();
            $programs = Program::with("departments.majors.courses")->whereHas("departments", function($res) use($roles){
                $res->whereIn("role_id",$roles);
            })->get();
            
            for($i=0; $i<count($programs); $i++){
                $countOfDepts = count($programs[$i]->departments);
                for($j=0; $j<$countOfDepts; $j++){
                    if(!in_array($programs[$i]->departments[$j]->role_id, $roles)){
                        unset($programs[$i]->departments[$j]);
                    }
                }
            }
            return $programs;
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
