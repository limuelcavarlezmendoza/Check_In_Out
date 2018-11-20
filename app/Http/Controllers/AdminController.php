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
  public function updateFileLeave(Request $request, Requestleave $leave)
  {
    $request->validate([
      'status' => 'required|string'
    ]);
    $leave->status = $request->status;


    switch($leave->status){
      case 'approved':
            $leave->save();
            return response()->json([
              'message' => 'Successfully Approved!',
              'status' => $leave->status
            ]);
            break;
      case 'cancelled':
            $leave->save();
            return response()->json([
              'message' => 'Successfully cancelled/denied!',
              'status' => $leave->status
            ]);
            break;
      case 'waiting':
            $leave->save();
            return response()->json([
              'message' => 'Successfully changed status to waiting!',
              'status' => $leave->status
            ]);
            break;
      default:
            return response()->json([
              'message' => 'invalid input'
            ]);
            break;
    }
  }

  public function updateFileScheduleChange(Request $request, Requestschedule $schedule)
  {
    $request->validate([
      'status' => 'required|string'
    ]);
    $schedule->status = $request->status;

    switch($schedule->status){
      case 'approved':
            $schedule->save();
            return response()->json([
              'message' => 'Successfully Approved!',
              'status' => $schedule->status
            ]);
            break;
      case 'cancelled':
            $schedule->save();
            return response()->json([
              'message' => 'Successfully cancelled!',
              'status' => $schedule->status
            ]);
            break;
      case 'waiting':
            $schedule->save();
            return response()->json([
              'message' => 'Successfully changed status to waiting!',
              'status' => $schedule->status
            ]);
            break;
      default:
            return response()->json([
              'message' => 'invalid input'
            ]);
            break;
    }
  }

  public function updateFileOfficialBusiness(Request $request, Requestob $officialBusiness)
  {
    $request->validate([
      'status' => 'required|string'
    ]);
    $officialBusiness->status = $request->status;

    switch($officialBusiness->status){
      case 'approved':
            $officialBusiness->save();
            return response()->json([
              'message' => 'Successfully Approved!',
              'status' => $officialBusiness->status
            ]);
            break;
      case 'cancelled':
            $officialBusiness->save();
            return response()->json([
              'message' => 'Successfully cancelled!',
              'status' => $officialBusiness->status
            ]);
            break;
      case 'waiting':
            $officialBusiness->save();
            return response()->json([
              'message' => 'Successfully changed status to waiting!',
              'status' => $officialBusiness->status
            ]);
            break;
      default:
            return response()->json([
              'message' => 'invalid input'
            ]);
            break;
    }
  }

    public function updateFileOvertime(Request $request, Requestot $overtime)
    {
      $request->validate([
        'status' => 'required|string'
      ]);
      $overtime->status = $request->status;

      switch($request->status){
        case 'approved':
              $overtime->save();
              return response()->json([
                'message' => 'Successfully Approved Overtime',
                'status' => $overtime->status
              ]);
              break;
        case 'cancelled':
              $overtime->save();
              return response()->json([
                'message' => 'Successfully cancelled!',
                'status' => $overtime->status
              ]);
              break;
        case 'waiting':
              $overtime->save();
              return response()->json([
                'message' => 'Successfully changed status to waiting',
                'status' => $overtime->status
              ]);
              break;
        default:
              return response()->json([
                'message' => 'invalid input'
              ]);
              break;
      }

    }

    public function getFileLeave()
    {
      $requestLeave = DB::table('requestleaves')->select('*')->get();

      return response()->json([
        'message' => 'List of Leave requests',
        'leaves' => $requestLeave
      ]);
    }

    public function getFileScheduleChange()
    {
      $requestScheduleChange = db::table('requestschedules')->select('*')->get();

      return response()->json([
        'message' => 'Lists of Schedule Change requests',
        'schedule_change' => $requestScheduleChange
      ]);
    }
    public function getFileOfficialBusiness()
    {
      $requestOfficialBusiness = db::table('requestobs')->select('*')->get();

      return response()->json([
        'message' => 'List of Official Business requests.',
        'official_business' => $requestOfficialBusiness
      ]);
    }

    public function getFileOvertime()
    {
      $requestOvertime = db::table('requestots')->select('*')->get();

      return response()->json([
        'message' => "List of Overtime Requests",
        'leaves' => $requestOvertime
      ]);
    }

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
