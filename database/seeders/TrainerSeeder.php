<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TrainerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'national_id' => "1000000000",
            'name'          => "مدرب ١",
            'email'         => NULL,
            'phone'         => "0511541111",
            'password' => Hash::make("123123123")
        ]);

        $user->trainer()->create([
            'bct_id'  => '002344',
            'qualification'    => 'ماجستير',
            'program_id'       => 1,
            'department_id'    => 1,
            'major_id'         => 1,
        ]);
    }
}
