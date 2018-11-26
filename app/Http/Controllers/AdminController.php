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
   public function __construct()
   {
     $this->middleware(['role:admin']);
   }
   public function onLeaveLog($date)
   {
      $onLeave = \App\Requestleave::where('date_start', '>=', $date)->get();

      return response()->json([
        'message' => 'list of on leave in given date',
        'onleaves' => $onLeave
      ]);
   }

   public function employeeLogFromTo($date_from, $date_to)
   {
     $timelog = DB::select("SELECT  id as 'attendanceId',
     (select action  from timelogs where timelogs.action = 'timein' and timelogs.attendance_id = attendanceId) as 'In',
     (select device_datetime from timelogs where timelogs.action = 'timein' and timelogs.attendance_id = attendanceId) as 'Time In' ,
     (select action from timelogs where timelogs.action = 'timeout' and timelogs.attendance_id = attendanceId) as 'Out',
     (select device_datetime from timelogs where timelogs.action = 'timeout' and timelogs.attendance_id = attendanceId) as 'Time Out'
     FROM attendances WHERE created_at BETWEEN '".date($date_from)."' AND '".date($date_to)."' "); // and timelogs.device_datetime >= $date

     return response()->json([
       'message' => $timelog
     ]);
   }

   public function employeeLogDate($date)
   { //where timelogs.device_datetime >= $date
     $timelog = DB::select("SELECT  id as 'attendanceId',
     (select action  from timelogs where timelogs.action = 'timein' and timelogs.attendance_id = attendanceId) as 'In',
     (select device_datetime from timelogs where timelogs.action = 'timein' and timelogs.attendance_id = attendanceId) as 'Time In' ,
     (select action from timelogs where timelogs.action = 'timeout' and timelogs.attendance_id = attendanceId) as 'Out',
     (select device_datetime from timelogs where timelogs.action = 'timeout' and timelogs.attendance_id = attendanceId) as 'Time Out'
     FROM attendances WHERE created_at like '%".date($date)."%'"); // and timelogs.device_datetime >= $date

     return response()->json([
       'message' => $timelog
     ]);

   }

  public function employeeLog()
  {
      $timelog = DB::select("SELECT  id as 'attendanceId',
  	  (select action  from timelogs where timelogs.action = 'timein' and timelogs.attendance_id = attendanceId) as 'In',
      (select device_datetime from timelogs where timelogs.action = 'timein' and timelogs.attendance_id = attendanceId) as 'Time In' ,
      (select action from timelogs where timelogs.action = 'timeout' and timelogs.attendance_id = attendanceId) as 'Out',
      (select device_datetime from timelogs where timelogs.action = 'timeout' and timelogs.attendance_id = attendanceId) as 'Time Out'
      FROM attendances");

      return response()->json([
        'message' => $timelog
      ]);
  }

  public function getEmployee($id)
  {
    $employee = DB::table('employees')->select('id', 'employee_number', 'device_type')->where('id', $id)->get();

    return response()->json([
      'message' => 'Employee',
      'employee' => $employee
    ]);
  }

  public function employeeList()
  {
    $employee = DB::table('employees')->select('id', 'employee_number', 'device_type')->get();

    return response()->json([
      'message' => 'List of Employees',
      'employees' => $employee
    ]);
  }

  public function updateFileLeave(Request $request, Requestleave $leave)
  {
    $request->validate([
      'status' => 'required|string'
    ]);
    $leave->status = $request->status;

    switch($leave->status){
      case 'approved':
            $leave->date_approved = Carbon::now('UTC');
            $leave->save();
            return response()->json([
              'message' => 'Successfully Approved!',
              'status' => $leave->status
            ]);
            break;
      case 'cancelled':
            $leave->date_declined = Carbon::now();
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
            $schedule->date_approved = Carbon::now();
            $schedule->save();
            return response()->json([
              'message' => 'Successfully Approved!',
              'status' => $schedule->status
            ]);
            break;
      case 'cancelled':
            $schedule->date_declined = Carbon::now();
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
            $officialBusiness->date_approved = Carbon::now();
            $officialBusiness->save();
            return response()->json([
              'message' => 'Successfully Approved!',
              'status' => $officialBusiness->status
            ]);
            break;
      case 'cancelled':
            $officialBusiness->date_declined = Carbon::now();
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
              $overtime->date_approved = Carbon::now();
              $overtime->save();
              return response()->json([
                'message' => 'Successfully Approved Overtime',
                'status' => $overtime->status
              ]);
              break;
        case 'cancelled':
              $overtime->date_declined = Carbon::now();
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

    public function assignRole(User $user)
    {
      $user->assignRole('admin');
      $role = $user->getRoleNames();
      return response()->json([
        'message' => 'Successfully added as',
        'role' => $role
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
      $updateDetails = array(
        'status' => 3
      );

      if(!$employee)
      {
          return response()->json([
            'message' => 'Employee not found.'
          ]);
      }

      $sameAccount = DB::table('employees')
                        ->where('employee_number', $employee->employee_number)
                        ->get();

      if(($employee->status == '2') || ($employee->status == '3'))
      {
          return response()->json([
            'message' => 'employee is already declined or replaced'
          ]);
      }
      else if($employee->status == '1')
      {
          return response()->json([
            'message' => 'employee is still active'
          ]);
      }

      if($sameAccount >= '2')
        {
            $sameAccount = DB::table('employees')
                           ->where('employee_number', $employee->employee_number)
                           ->where('status', '=', 1)
                           ->update($updateDetails);
        }
        $user = $employee->user;
        $user->assignRole('employee');
        $role = $user->getRoleNames();

       $employee->status = 1;
       $employee->save();

       return response()->json([
         'message' => 'Employee approved',
         'status' => $employee->status,
         'role' => $role
       ]);
    }

    public function inviteAdmin(Request $request)
    {
      $request->validate([
        'employee_id' => 'required|integer|exists:employees,id',
        'employee_number' => 'required|string|exists:employees,employee_number'
      ]);
      $employeeChecker = DB::table('users')
                      ->where('employee_number', $request->employee_number)
                      ->first();
      if(isset($employeeChecker))
      {
        if($employeeChecker->status !== '1')
        {
          return response()->json([
            'message' => 'Employee already invited, waiting to register.'
          ]);
        }
      }

      $employee = \App\Employee::find($request->employee_id);

      if(!$employee)
      {
          return response()->json([
          'message' => 'Employee not found.'
        ]);
      }

      $user = new \App\User([
        'employee_number' => $request->employee_number
      ]);
      $user->save();

      return response()->json([
        'message' => 'User Saved! Now user can register as admin.',
        'user' => $user->employee_number
      ]);
    }


}
