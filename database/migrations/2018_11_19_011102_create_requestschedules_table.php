<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestschedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requestschedules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id');
            $table->string('reason');
            $table->dateTime('date_requested');
            $table->time('preffered_time_in'); //hh:mm:ss
            $table->time('preffered_time_out'); //hh:mm:ss
            $table->string('status');
            $table->string('approved_by')->nullable();
            $table->dateTime('date_approved')->nullable();
            $table->string('declined_by')->nullable();
            $table->dateTime('date_declined')->nullable();
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
        Schema::dropIfExists('requestschedules');
    }
}
