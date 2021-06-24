<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CoursesAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->integer('theoretical_hours')->default(0);
            $table->integer('practical_hours')->default(0);
            $table->integer('exam_theoretical_hours')->default(0);
            $table->integer('exam_practical_hours')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            // $table->dropColumn('theoretical_hours');
            // $table->dropColumn('practical_hours');
            // $table->dropColumn('exam_theoretical_hours');
            // $table->dropColumn('exam_practical_hours');
        });
    }
}
