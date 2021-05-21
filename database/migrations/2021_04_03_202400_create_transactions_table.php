<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger("manager_id");
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('refund_order_id')->nullable();
            $table->unsignedBigInteger('semester_id');
            $table->double("amount");
            $table->string("note")->nullable();
            $table->string("type");
            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('manager_id')->references('id')->on('managers');
            // $table->foreign("payment_id")->references("id")->on("payments");
            // $table->foreign("order_id")->references("id")->on("orders");
            // $table->foreign("refund_order_id")->references("id")->on("refund_orders");
            $table->foreign('semester_id')->references('id')->on('semesters');
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
        Schema::dropIfExists('transactions');
    }
}
