<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainerCoursesOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainer_courses_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('trainer_id');
            $table->unsignedBigInteger('semester_id');
            $table->string('course_type');
            $table->integer('count_of_students')->default(0);
            $table->integer('division_number')->default(0);
            $table->boolean("accepted_by_dept_boss")->nullable();
            $table->boolean("accepted_by_community")->nullable();
            $table->boolean("accepted_by_dean")->nullable();
            $table->timestamps();
            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('trainer_id')->references('id')->on('trainers');
            $table->foreign('semester_id')->references('id')->on('semesters');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trainer_courses_orders');
    }
}
