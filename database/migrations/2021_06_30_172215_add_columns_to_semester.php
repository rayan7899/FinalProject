<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToSemester extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('semesters', function (Blueprint $table) {
            $table->integer('which_semester')->default(0); //1 or 2 if 0 it means summer
            $table->integer('count_of_weeks');
            $table->string('name');
            $table->date("contract_date")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('semesters', function (Blueprint $table) {
            $table->dropColumn('which_semester');
            $table->dropColumn('name');
            $table->dropColumn('contract_date');
            $table->dropColumn('count_of_weeks');
        });
    }
}
