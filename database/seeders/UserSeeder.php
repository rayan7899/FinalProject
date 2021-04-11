<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
        $user = User::create([
            'national_id' => "1111111111",
            'name'          => "موظف خدمة المجتمع",
            'email'         => NULL,
            'phone'         => "0511111111",
            'password' => Hash::make("12345678")
        ]);

        $user->manager()->create();
        $user->manager->permissions()->create([
            "role_id" => 1, // موظف خدمة المجتمع
        ]);

        $user = User::create([
            'national_id' => "2222222222",
            'name'          => "موظف شؤون المتدربين",
            'email'         => NULL,
            'phone'         => "0522222222",
            'password' => Hash::make("12345678")
        ]);

        $user->manager()->create();
        $user->manager->permissions()->create([
            "role_id" => 2, // شؤون المتدربين
        ]);

        $user = User::create([
            'national_id' => "3333333333",
            'name'          => "موظف الإرشاد",
            'email'         => NULL,
            'phone'         => "0533333333",
            'password' => Hash::make("12345678")
        ]);

        $user->manager()->create();
        $user->manager->permissions()->create([
            "role_id" => 3, // الإرشاد
        ]);
    }
}
