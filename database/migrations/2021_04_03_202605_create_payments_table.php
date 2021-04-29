<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->double("amount");
            $table->boolean("accepted")->nullable();
            $table->text("note")->nullable();
            $table->string("receipt_file_id");
            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign("transaction_id")->references("id")->on("transactions");


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
