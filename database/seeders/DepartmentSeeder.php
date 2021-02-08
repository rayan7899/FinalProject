<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    static $departments = [
        "التقنية الميكانيكية",
        "التقنية الادارية",
        "التقنية الالكترونية",
        "التقنية الكهربائية",
        "التقنية الكيميائية",
        "التقنية المدنية والمعمارية",
        "تقنية السياحة والفندقة",
        "انتاج غذائي",
        "تقنية الاتصالات",
        "تقنية البيئة",
        "تقنية الحاسب الآلي"
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this::$departments as $department){
            Department::create([
                'name' => $department,
            ]);
        }
    }
}
