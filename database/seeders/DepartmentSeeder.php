<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{

    static $baccDepts = [
        "تقنية الحاسب الآلي",
        "التقنية الكهربائية",
        "التقنية الميكانيكية",
        "التقنية الادارية",
    ];

    static $diplomDepts = [
        "تقنية الحاسب الآلي",
        "التقنية الكهربائية",
        "التقنية الادارية",
        "التقنية الالكترونية"
    ];


    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
        foreach ($this::$baccDepts as $deptName) {
            Department::create([
                'name' => $deptName,
                'program_id' => 1
            ]);
        }

        foreach ($this::$diplomDepts as $diplomDeptName) {
            Department::create([
                'name' => $diplomDeptName,
                'program_id' => 2
            ]);
        }
    }
}
