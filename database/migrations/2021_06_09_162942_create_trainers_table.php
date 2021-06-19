<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('computer_number')->unique();
            $table->string('qualification');
            $table->unsignedBigInteger('program_id');
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('major_id');
            $table->timestamps();
            $table->foreign('program_id')->references('id')->on('programs');
            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('major_id')->references('id')->on('majors');
            $table->foreign('user_id')->references('id')->on('users')->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trainers');
    }
}
