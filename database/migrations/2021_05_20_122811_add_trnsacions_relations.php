<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrnsacionsRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('transactions', function (Blueprint $table) {
         $table->foreign('payment_id')->references('id')->on('payments');
         $table->foreign('order_id')->references('id')->on('orders');
         $table->foreign('refund_order_id')->references('id')->on('refund_orders');
         }
         );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
