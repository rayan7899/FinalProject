<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Program;
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
            ProgramSeeder::class,
            DepartmentSeeder::class,
            MajorSeeder::class,
            //StudentSeeder::class,
            ]);
    }
}
