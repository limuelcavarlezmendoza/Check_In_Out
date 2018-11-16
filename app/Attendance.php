<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
      'employee_id', 'status', 'action', 'latitude', 'longitude', 'device_datetime',
      'server_datetime', 'timezone', 'remarks', 'work_status'
    ];

    protected $hidden = [
      'status',
    ];
}
