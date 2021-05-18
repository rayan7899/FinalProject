<?php

namespace Database\Seeders;

use App\Models\Semester;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Semester::create([
            "start_date" => Carbon::today(),
            "end_date" => null,
        ]);
    }
}
