<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Role;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{

    static $baccDepts = [
        "الحاسب وتقنية المعلومات",
        "التقنية الكهربائية",
        "التقنية الميكانيكية",
        "التقنية الإدارية",
    ];

    static $diplomDepts = [
        "الحاسب وتقنية المعلومات",
        "التقنية الكهربائية",
        "التقنية الإدارية",
        "التقنية اﻹلكترونية"
    ];


    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
        foreach ($this::$baccDepts as $deptName) {
            $role = Role::create([
                'name' => $deptName . ' - بكالوريوس ',

            ]);
            Department::create([
                'name' => $deptName,
                'program_id' => 1,
                'role_id' => $role->id
            ]);
        }

        foreach ($this::$diplomDepts as $deptName) {
            $role = Role::create([
                    'name' => $deptName . ' - دبلوم ',
                ]);
                
            Department::create([
                'name' => $deptName,
                'program_id' => 2,
                'role_id' => $role->id

            ]);
        }
    }
}
