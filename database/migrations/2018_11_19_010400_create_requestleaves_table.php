<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestleavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requestleaves', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id');
            $table->integer('leave_id');
            $table->string('reason');
            $table->integer('days_count');
            $table->dateTime('date_requested');
            $table->date('date_start');
            $table->date('date_end');
            $table->string('status'); //approved , waiting , cancelled,
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
        Schema::dropIfExists('requestleaves');
    }
}
