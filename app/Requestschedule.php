<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Requestschedule extends Model
{
    protected $fillable = [
      'employee_id', 'reason', 'date_requested', 'preffered_time_in', 'preffered_time_out',
      'status', 'approved_by', 'date_approved', 'declined_by', 'date_declined',
    ];

    protected $hidden = [

    ];
}
