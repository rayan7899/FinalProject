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
            $table->string('rayat_id')->unique()->nullable();
            $table->integer('birthdate')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('program_id');
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('major_id');
            $table->integer('level')->default(1);
            $table->integer('credit_hours')->default(0);
            $table->boolean("studentState")->default(true);
            $table->boolean('agreement')->default(false);
            $table->boolean('data_updated')->default(false);
            $table->boolean('student_docs_verified')->default(false); // verified degree , identity documents.
            $table->string('has_imported_docs'); 
            $table->boolean('final_accepted')->default(false);
            $table->double('wallet')->default(0);
            $table->boolean('walletUpdated')->default(false);
            $table->string('traineeState')->default('trainee');
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
        Schema::dropIfExists('students');
    }
}
