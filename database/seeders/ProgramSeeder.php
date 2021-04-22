<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Program;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Program::create([
            'name' => 'البكالوريوس',
            'hourPrice' => 550,
        ]);

        Program::create([
            'name' => 'الدبلوم',
            "hourPrice" => 400,
        ]);
    }
}
