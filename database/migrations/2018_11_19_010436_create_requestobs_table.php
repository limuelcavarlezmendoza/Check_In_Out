<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requestobs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id');
            $table->string('reason');
            $table->dateTime('date_requested');
            $table->date('date_from');
            $table->date('date_to');
            $table->string('site');
            $table->string('client');
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
        Schema::dropIfExists('requestobs');
    }
}
