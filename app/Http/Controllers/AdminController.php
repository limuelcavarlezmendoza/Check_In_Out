<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\Employee;
use App\Attendance;

class AdminController extends Controller
{
    public function approveEmployee($id)
    {
      $employee = \App\Employee::find($id);
      if(!$employee)
      {
          return response()->json([
            'message' => 'Employee not found.'
          ]);
      }
      $employee->status = 'approved';
      $employee->save();

      return response()->json([
        'message' => 'Employee approved',
        'status' => $employee->status
      ]);
    }


}
