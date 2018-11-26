<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\Employee;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;

class AuthController extends Controller
{
  public function employeeRegister(Request $request)
  {
    $request->validate([
      'employee_number' => 'required|string|unique:employees,employee_number',
      'password' => 'required|string|confirmed',
      'device_id' => 'required|string',
      'device_type' => 'required|string'
    ]);

    $user = new User([
      'employee_number' => $request->employee_number,
      'password' => bcrypt($request->password)
    ]);
    $user->save();

    $employee = new Employee([
      'employee_number' => $request->employee_number,
      'device_id' => $request->device_id,
      'device_type' => $request->device_type
      //status has a default value of 0
    ]);
    $user->employee()->save($employee);

    return response()->json([
      'message' => 'Employee Successfully Created',
    ]);
  }

  public function employeeLogin(Request $request)
  {
    $request->validate([
      'employee_number' => 'required|string',
      'password' => 'required|string'
    ]);

    $employee = \App\User::find(1)->employee()
                    ->where('employee_number', $request->employee_number)
                    ->first();


    $user = $employee->user;
    // dd($employee->user);
    if(!isset($user))
    {
      return response()->json([
        'message' => 'User not found. Please make sure that user is required to register!'
      ]);
    }

    $credentials = [
      'employee_number' => $request->employee_number,
      'password' => $request->password
  ];

    if(!Auth::guard('web')->attempt($credentials))
        return response()->json([
          'message' => 'Unauthorized'
        ], 401);

    $tokenResult = $user->createToken('Personal Access Token');
    $token = $tokenResult->token;

    $token->save();

    return response()->json([
        'access_token' => $tokenResult->accessToken,
        'token_type' => 'Bearer',
        'expires_at' => Carbon::parse(
            $tokenResult->token->expires_at
          )->toDateTimeString()
    ]);
  }

  /*
  ------------------------------------------------------------------------------------------
    public function adminLogin(Request $request)
    {
      $request->validate([
        'employee_number' => 'required|string',
        'password' => 'required|string'
      ]);

      $user = \App\User::where('employee_number', $request->employee_number)->first();

      if(!isset($user))
      {
        return response()->json([
          'message' => 'User not found. Please make sure that user is required to register!'
        ]);
      }

      if($user->status !== '1'){
        return response()->json([
          'message' => 'Please register first.'
        ]);
      }

      $credentials = request(['employee_number', 'password']);

      if(!Auth::guard('web')->attempt($credentials))
          return response()->json([
            'message' => 'Unauthorized'
          ], 401);

      // $user = $request->user();

      $tokenResult = $user->createToken('Personal Access Token');
      $token = $tokenResult->token;

      $token->save();

      return response()->json([
          'access_token' => $tokenResult->accessToken,
          'token_type' => 'Bearer',
          'expires_at' => Carbon::parse(
              $tokenResult->token->expires_at
            )->toDateTimeString()
      ]);
    }

    public function logout(Request $request)
    {
      $request->user()->token()-revoke();

      return response()->json([
        'message' => 'Successfully logged out'
      ]);
    }

    public function adminRegister(Request $request)
    {
      $request->validate([
        'employee_number' => 'required|string|exists:users,employee_number',
        'password' => 'required|string'
      ]);

      $user = \App\User::where('employee_number', $request->employee_number)->first();
      if($user->status == 1){
        return response()->json([
          'message' => 'Already registered'
        ]);
      }
      $user->password = bcrypt($request->password);
      $user->status = 1;
      $user->save();

      $user->assignRole('admin');
      $role = $user->getRoleNames();

      return response()->json([
        'message' => 'Admin successfully created user!',
        'role' => $role
      ], 201);
    }
    ------------------------------------------------------------------------------------------
    */
}
