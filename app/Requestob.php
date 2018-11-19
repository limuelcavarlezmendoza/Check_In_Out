<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Requestob extends Model
{
    protected $fillable = [
      'employee_id', 'reason', 'date_requested', 'date_from', 'date_to',
      'site', 'client', 'status', 'approved_by', 'date_approved',
      'declined_by', 'date_declined'
    ];

    protected $hidden = [

    ];
}
