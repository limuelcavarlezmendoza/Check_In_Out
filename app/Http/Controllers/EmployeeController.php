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
use DB;

class EmployeeController extends Controller
{
  public function __construct()
  {
    $this->middleware(['role:employee']);
  }

  /*
  * normalFlex 7-9 if 9:10 up then late true
  *crmFlex 7-11 if 11:10 up then late
  *
  *
  *
  *
  *
  *
  *
  */
  public function employeeLate($id)
  {
    $employee = \App\Employee::where('id', $id)
                              ->select('id', 'employee_number')->get();


  }

  public function employeeLeave($id)
  {
    $employee = \App\Employee::where('id', $id)
                              ->select('id', 'employee_number')->get();

    $leavelogs = \App\Requestleave::where('employee_id', $id)
                              ->select('reason', 'days_count',
                              'date_requested', 'date_start',
                              'date_end', 'status', 'approved_by', 'date_approved',
                              'declined_by', 'date_declined')->get();
    return response()->json([
      'message' => 'list of all leaves',
      'employee' => $employee,
      'leaves' => $leavelogs
    ]);
  }

  public function employeeLogs($id)
  {
    //get id and number
    $employee = \App\Employee::where('id', $id)
                ->select('id', 'employee_number')->get();
    //get logs
    $logs = \App\Timelog::where('employee_id', $id)
                          ->select('status', 'action',
                          'device_datetime', 'timezone',
                            'remarks', 'work_status')->get();

    return response()->json([
      'message' => 'list of all logs',
      'employee' => $employee,
      'logs' => $logs
    ]);

  }



  public function FileLeaveCancellation(Request $request, $requestId)
  {
    $leave = \App\Requestleave::findOrFail($requestId);
    $leave->status = 'cancelled';
    $leave->approved_by = 'cancelled';
    $leave->declined_by = 'cancelled';
    $leave->save();

    return response()->json([
      'message' => 'Leave cancelled successfully.',
      'status' => $leave->status
    ]);
  }

  public function fileLeave(Request $request)
  {
    $request->validate([
      'employee_id' => 'required|integer',
      'leave_id' => 'required|integer',
      'reason' => 'required|string',
      'days_count' => 'required|integer',
      'date_requested' => 'required|date_format:"Y-m-d H:i:s"',
      'date_start' => 'required|date_format:"Y-m-d"',
      'date_end' => 'required|date_format:"Y-m-d"'
      // 'status' => 'required|string'
      // 'approved_by' => 'required|string',
      // 'date_approved' => 'required|date_format:"Y-m-d H:i:s"',
      // 'declined_by' => 'required|string',
      // 'date_declined' => 'required|date_format:"Y-m-d H:i:s"',
    ]);
    //now get in some default values in the other columns
    $leave = new \App\Requestleave([
      'employee_id' => $request->employee_id,
      'leave_id' => $request->leave_id,
      'reason' => $request->reason,
      'days_count' => $request->days_count,
      'date_requested' => $request->date_requested,
      'date_start' => $request->date_start,
      'date_end' => $request->date_end,
      'status' => 'waiting'
    ]);

    $leave->save();

    return response()->json([
      'message' => 'Schedule change request sent, wait for approval',
      'status' => $leave->status
    ]);
  }

  public function fileScheduleChange(Request $request)
  {
    $request->validate([
      'employee_id' => 'required|integer',
      'reason' => 'required|string',
      'date_requested' => 'required|date_format:"Y-m-d H:i:s"',
      'preffered_time_in' => 'required|date_format:"H:i:s"',
      'preffered_time_out' => 'required|date_format:"H:i:s"'
      // 'status' => 'required|string'
      // 'approved_by' => 'required|string',
      // 'date_approved' => 'required|date_format:"Y-m-d H:i:s"',
      // 'declined_by' => 'required|string',
      // 'date_declined' => 'required|date_format:"Y-m-d H:i:s"',
    ]);
    //now get in some default values in the other columns
    $scheduleChange = new \App\Requestschedule([
      'employee_id' => $request->employee_id,
      'reason' => $request->reason,
      'date_requested' => $request->date_requested,
      'preffered_time_in' => $request->preffered_time_in,
      'preffered_time_out' => $request->preffered_time_out,
      'status' => 'waiting'
    ]);

    $scheduleChange->save();

    return response()->json([
      'message' => 'Schedule change request sent, wait for approval',
      'status' => $scheduleChange->status
    ]);
  }

  public function fileOfficialBusiness(Request $request)
  {
    $request->validate([
      'employee_id' => 'required|integer',
      'reason' => 'required|string',
      'date_requested' => 'required|date_format:"Y-m-d H:i:s"',
      'date_from' => 'required|date_format:"Y-m-d"',
      'date_to' => 'required|date_format:"Y-m-d"',
      'site' => 'required|string',
      'client' => 'required|string',
      // 'status' => 'required|string'
      // 'approved_by' => 'required|string',
      // 'date_approved' => 'required|date_format:"Y-m-d H:i:s"',
      // 'declined_by' => 'required|string',
      // 'date_declined' => 'required|date_format:"Y-m-d H:i:s"',
    ]);
    //now get in some default values in the other columns
    $officialBusiness = new \App\Requestob([
      'employee_id' => $request->employee_id,
      'reason' => $request->reason,
      'date_requested' => $request->date_requested,
      'date_from' => $request->date_from,
      'date_to' => $request->date_to,
      'site' => $request->site,
      'client' => $request->client,
      'status' => 'waiting'
    ]);

    $officialBusiness->save();

    return response()->json([
      'message' => 'Official business request sent, wait for approval',
      'status' => $officialBusiness->status
    ]);
  }

  public function fileOverTime(Request $request)
  {
    $request->validate([
      'employee_id' => 'required|integer',
      'reason' => 'required|string',
      'date_requested' => 'required|date_format:"Y-m-d H:i:s"',
      'date_of_ot' => 'required|date_format:"Y-m-d"',
      'time_from' => 'required|date_format:"Y-m-d H:i:s"',
      'time_to' => 'required|date_format:"Y-m-d H:i:s"',
      //'status' => 'required|string'
    ]);

    //now get in some default values in the other columns
    $overTime = new \App\Requestot([
      'employee_id' => $request->employee_id,
      'reason' => $request->reason,
      'date_requested' => $request->date_requested,
      'date_of_ot' => $request->date_of_ot,
      'time_from' => $request->time_from,
      'time_to' => $request->time_to,
      'status' => 'waiting'
    ]);

    $overTime->save();

    return response()->json([
      'message' => 'OverTime request sent, wait for approval',
      'status' => $overTime->status
    ]);
  }

  public function employeeRegister(Request $request)
  {
    $request->validate([
       'employee_number' => 'required|string',
       'device_id' => 'required|string',
       'device_type' => 'required|string',
    ]);

    $employee = DB::table('employees')
                      ->where('employee_number', '=', $request->employee_number)
                      ->get();

    $checkStatus = DB::table('employees')
                        ->where('employee_number', '=', $request->employee_number)
                        ->whereIn('status', [0])
                        ->get();
    // dd($checkStatus);
    if(count($checkStatus) >= '1')
      {
        return response()->json([
          'message' => 'Employee number is already waiting for approval'
        ]);
      }

    $employee = new \App\Employee([
      'employee_number' => $request->employee_number,
      'device_type' => $request->device_type,
      'device_id' => $request->device_id,
    ]);
    $employee->save();
    return response()->json([
      'message' => 'User Successfully Created!'
    ]);
  }

  public function employeeTimeIn(Request $request)
  {
      // STORE THE DATA
    $request->validate([
      'employee_id' => 'required|integer',
      'device_id' => 'required|string',
      'status' => 'required|string', //attendance or manual
      'action' => 'required|string',
      'latitude' => 'required|string',
      'longitude' => 'required|string',
      'device_datetime' => 'required|date_format:"Y-m-d H:i:s"',
      'timezone' => "required|string",
      'work_status' => 'required|string' // onfield or office
    ]);

      $attendance = new \App\Attendance([
        'employee_id' => $request->employee_id
      ]);
      $attendance->save();

      $timelog = new \App\Timelog;
      $timelog->employee_id = $request->employee_id;
      //attendance
      $timelog->attendance_id = $attendance->id;
      $timelog->status = $request->status;
      $timelog->action = $request->action;
      $timelog->latitude = $request->latitude;
      $timelog->longitude = $request->longitude;
      $timelog->device_datetime = $request->device_datetime;
      $timelog->server_datetime = date('Y-m-d H:i:s');
      $timelog->timezone = $request->timezone;
      $timelog->work_status = $request->work_status;
      $timelog->save();
    // STORE THE DATA

   /* if request[action] == "in"
   *  add new data on table attendance
   *  after adding get the id of attendance
   * --------------------
   * use the attendance_id to insert new data on time_log table
   */

    return response()->json([
      'message' => "Successfully Checked In.",
      'checkinTime' => $request->device_datetime
    ]);
  }

  public function employeeTimeOut(Request $request)
  {
    $request->validate([
      'employee_id' => 'required|integer',
      'device_id' => 'required|string',
      'status' => 'required|string', //attendance or manual
      'action' => 'required|string',
      'latitude' => 'required|string',
      'longitude' => 'required|string',
      'device_datetime' => 'required|date_format:"Y-m-d H:i:s"',
      'timezone' => "required|string",
      'work_status' => 'required|string' // onfield or office
    ]);

    $attendance = \App\Attendance::where('employee_id', $request->employee_id)
                                  ->orderBy('id', 'desc')
                                  ->first();

  //  $attendance = \App\Attendance::findOrFail($request->employee_id);
    $timelog = new \App\Timelog;
    $timelog->employee_id = $request->employee_id;
    //attendance
    $timelog->attendance_id = $attendance->id;  //fix the error
    $timelog->status = $request->status;
    $timelog->action = $request->action;
    $timelog->latitude = $request->latitude;
    $timelog->longitude = $request->longitude;
    $timelog->device_datetime = $request->device_datetime;
    $timelog->server_datetime = date('Y-m-d H:i:s');
    $timelog->timezone = $request->timezone;
    $timelog->work_status = $request->work_status;
    $timelog->save();
    // STORE THE DATA

    return response()->json([
      'message' => "Successfully Checked Out.",
      'checkinTime' => $request->device_datetime
    ]);

  }



}
