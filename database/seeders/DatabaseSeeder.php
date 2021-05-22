<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Department;
use App\Models\Program;
use App\Models\Role;
use App\Models\Semester;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            RoleSeeder::class,
            ProgramSeeder::class,
            DepartmentSeeder::class,
            MajorSeeder::class,
            CourseSeeder::class,
            UserSeeder::class,
            // StudentSeeder::class,
            // SemesterSeeder::class,
            ]);
    }
}
