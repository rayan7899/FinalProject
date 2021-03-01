<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    static $names = [
        'محمد',
        'عبدالله',
        'خالد',
        'يوسف',
        'تركي',
        'نايف',
        'ريات',
        'سلمان',
        'حاتم',
        'ياسر',
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i <= 50; $i++) {

            $user = User::create([
                'national_id' => "10000" . mt_rand(10000, 99999),
                'name'          => $this::$names[mt_rand(0, count($this::$names) - 1)] . " " . $this::$names[mt_rand(0, count($this::$names) - 1)] . " " . $this::$names[mt_rand(0, count($this::$names) - 1)],
                'email'         => NULL,
                'phone'         => "05522" . mt_rand(10000, 99999),
                'password' => Hash::make("bct12345")
            ]);
            $user->student()->create([
                'user_id'          => $user->id,
                'birthdate'        => mt_rand(1400, 1430),
                'program_id'       => 1,
                'department_id'    => 1,
                'major_id'         => 1,
            ]);



            $user = User::create([
                'national_id' => "1000000" . mt_rand(10000, 99999).$i,
                'name'          => $this::$names[mt_rand(0, count($this::$names) - 1)] . " " . $this::$names[mt_rand(0, count($this::$names) - 1)] . " " . $this::$names[mt_rand(0, count($this::$names) - 1)],
                'email'         => NULL,
                'phone'         => "05522" . mt_rand(10000, 99999),
                'password' => Hash::make("bct12345")
            ]);
            $user->student()->create([
                'user_id'          => $user->id,
                'birthdate'        => mt_rand(1400, 1430),
                'program_id'       => 2,
                'department_id'    => 7,
                'major_id'         => 11,
            ]);



            $user = User::create([
                'national_id' => "1000000" . mt_rand(10000, 99999).$i,
                'name'          => $this::$names[mt_rand(0, count($this::$names) - 1)] . " " . $this::$names[mt_rand(0, count($this::$names) - 1)] . " " . $this::$names[mt_rand(0, count($this::$names) - 1)],
                'email'         => NULL,
                'phone'         => "05522" . mt_rand(10000, 99999),
                'password' => Hash::make("bct12345")
            ]);
            $user->student()->create([
                'user_id'          => $user->id,
                'birthdate'        => mt_rand(1400, 1430),
                'program_id'       => 2,
                'department_id'    => 6,
                'major_id'         => 10,
            ]);



            $user = User::create([
                'national_id' => "1000000" . mt_rand(10000, 99999).$i,
                'name'          => $this::$names[mt_rand(0, count($this::$names) - 1)] . " " . $this::$names[mt_rand(0, count($this::$names) - 1)] . " " . $this::$names[mt_rand(0, count($this::$names) - 1)],
                'email'         => NULL,
                'phone'         => "05522" . mt_rand(10000, 99999),
                'password' => Hash::make("bct12345")
            ]);
            $user->student()->create([
                'user_id'          => $user->id,
                'birthdate'        => mt_rand(1400, 1430),
                'program_id'       => 1,
                'department_id'    => 4,
                'major_id'         => 5,
            ]);
        }
    }
}
