<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\Employee;
use App\Requestot;
use App\Requestob;
use App\Requestleave;
use App\Requestschedule;
use App\Timelog;
use App\Attendance;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;

class AdminController extends Controller
{
    public function deleteRole(Request $request)
    {
      $request->validate([
        'name' => 'required|string|confirmed'
      ]);
      DB::table('roles')->where('name', $request->name)->delete();

      return response()->json([
        'message' => 'Role deleted.',
        'role' => $request->name
      ]);
    }
    public function listRole(Request $request)
    {
      $roles = DB::table('roles')->pluck('name');

      return response()->json([
        'message' => 'list of roles',
        'roles' => $roles
      ]);
    }

    public function createRole(Request $request)
    {
      $request->validate([
        'name' => 'required|string|unique:roles,name|confirmed'
      ]);

      $role = Role::create(['name' => $request->name]);
      return response()->json([
        'message' => 'Role created successfully',
        'role' => $role->name
      ]);
    }

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
