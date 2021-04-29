<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->integer("amount");
            $table->text("reason");
            $table->text("IBAN");
            $table->text("bank");
            $table->text("note")->nullable();

            // refund order accepted state
            // null = waiting
            // true = accepted
            // false = rejected
            $table->boolean("accepted")->nullable();
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
        Schema::dropIfExists('refund_orders');
    }
}
