<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimelogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timelogs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('employee_id');
            $table->integer('attendance_id');
            $table->string('status')->nullable();// attendance or manual
            $table->string('action'); //check in or check out
            $table->string('latitude');
            $table->string('longitude');
            $table->dateTime('device_datetime'); //Y-m-d h-m-s
            $table->dateTime('server_datetime');
            $table->string('timezone');
            $table->string('remarks')->nullable();
            $table->string('work_status');
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
        Schema::dropIfExists('timelogs');
    }
}
