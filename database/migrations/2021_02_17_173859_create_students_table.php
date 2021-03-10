<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            $table->integer('birthdate')->nullable();
            $table->unsignedBigInteger('rayat_id')->default(0);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('program_id');
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('major_id');
            $table->boolean('agreement')->default(false);
            $table->boolean('data_updated')->default(false);
            $table->boolean('documents_verified')->default(false);
            $table->boolean('final_accepted')->default(false);
            $table->double('wallet')->default(0);
            $table->string('traineeState')->default('trainee');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->foreign('program_id')->references('id')->on('programs');
            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('major_id')->references('id')->on('majors');
            $table->foreign('user_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
