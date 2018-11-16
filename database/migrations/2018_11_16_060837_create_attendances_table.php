<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->increments('id');
            $table->string('employee_id');
            $table->string('status')->nullable();// approved, waiting, cancelled
            $table->string('action'); //check in or check out
            $table->string('latitude');
            $table->string('longitude');
            $table->dateTime('device_datetime'); //Y-m-d h-m-s
            $table->dateTime('server_datetime');
            $table->string('timezone');
            $table->string('remarks');
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
        Schema::dropIfExists('attendances');
    }
}
