<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
      'employee_number', 'device_type', 'firebase_token', 'device_id', 'is_registered'
    ];

    protected $hidden = [
      'is_registered',
    ];

  public function user()
  {
    return $this->belongsTo(\App\User::class);
  }
}
