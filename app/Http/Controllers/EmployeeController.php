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
use App\Attendance;
use DB;

class EmployeeController extends Controller
{
  public function employeeLeave($id)
  {
    $employee = \App\Employee::where('id', $id)
                  ->select('id', 'employee_number')->get();

    $leaves = \App\Requestleave::where('id', $id)

  }

  public function employeeLogs($id)
  {
    //emp name id
    //emp.attendance
    $employee = \App\Employee::where('id', $id)
                ->select('id', 'employee_number')->get();

    // $employee = DB::table('employees')->select('id', 'employee_number')
    //                                   ->where('id', $id)->get();

    $logs = \App\Attendance::where('employee_id', $id)
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
       'employee_number' => 'required|string|unique:Employees,employee_number',
       'device_id' => 'required|string',
       'device_type' => 'required|string',
    ]);

    $employee = new \App\Employee([
      'employee_number' => $request->employee_number,
      'device_type' => $request->device_type,
      'device_id' => $request->device_id,
      'status' => 'waiting'
    ]);
    $employee->save();
    return response()->json([
      'message' => 'User Successfully Created!'
    ]);
  }


// STILL NEEDS TO STORE THE dATA TO THE DATABASE!!!

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

    $attendance = new \App\Attendance;
    $attendance->employee_id = $request->employee_id;
    $attendance->status = $request->status;
    $attendance->action = $request->action;
    $attendance->latitude = $request->latitude;
    $attendance->longitude = $request->longitude;
    $attendance->device_datetime = $request->device_datetime;
    $attendance->server_datetime = date('Y-m-d H:i:s');
    $attendance->timezone = $request->timezone;
    $attendance->work_status = $request->work_status;
    $attendance->save();
    // STORE THE DATA

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

    $attendance = new \App\Attendance;
    $attendance->employee_id = $request->employee_id;
    $attendance->status = $request->status;
    $attendance->action = $request->action;
    $attendance->latitude = $request->latitude;
    $attendance->longitude = $request->longitude;
    $attendance->device_datetime = $request->device_datetime;
    $attendance->server_datetime = date('Y-m-d H:i:s');
    $attendance->timezone = $request->timezone;
    $attendance->work_status = $request->work_status;
    $attendance->save();
    // STORE THE DATA

    return response()->json([
      'message' => "Successfully Checked Out.",
      'checkinTime' => $request->device_datetime
    ]);
  }



}
