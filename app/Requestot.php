<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Requestot extends Model
{
    protected $fillable = [
      'employee_id', 'reason', 'date_requested', 'date_of_ot', 'time_from', 'time_to',
      'status', 'approved_by', 'date_approved', 'declined_by', 'date_declined',
    ];

    protected $hidden = [

    ];
}
