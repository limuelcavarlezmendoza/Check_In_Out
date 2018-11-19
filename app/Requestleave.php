<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Requestleave extends Model
{
    protected $fillable = [
      'employee_id', 'leave_id', 'reason', 'days_count', 'date_requested',
      'date_start', 'date_end', 'status', 'approved_by', 'date_approved',
      'declined_by', 'date_declined',
    ];

    protected $hidden = [

    ];
}
