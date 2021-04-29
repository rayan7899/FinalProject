<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->integer("requested_hours");
            $table->double("amount");
            $table->double("discount");
            $table->text("note")->nullable();
            // for private state only
            $table->string("private_doc_file_id")->nullable();
            // private_doc_verified
            // null = waiting
            // true = accepted
            // false = rejected
            $table->boolean("private_doc_verified")->nullable();
            // -----------
            $table->timestamps();
            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign("transaction_id")->references("id")->on("transactions");
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
