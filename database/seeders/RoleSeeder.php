<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            "name" => "خدمة المجتمع"
        ]);
        
        Role::create([
            "name" => "شؤون المتدربين"
        ]);

        Role::create([
            "name" => "الإرشاد"
        ]);
        
    }
}
