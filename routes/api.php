<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
  'prefix' => 'auth'
], function (){
    // /admin/login
    Route::post('/employee/login', 'AuthController@employeeLogin');
    Route::post('register', 'AuthController@employeeRegister');
});

Route::group([
    'prefix' => 'admin'
], function () {



      Route::group([
       'middleware' => ['auth:api', 'role:admin']
      ], function(){
      Route::post('/register/invite', 'AdminController@inviteAdmin');
      Route::post('/add/{id}', 'AdminController@addAdmin'); //add admin
      Route::put('/approve/{id}', 'AdminController@approveEmployee');
      Route::post('/status/{id}', 'AdminController@changeStatus');// change inactive/active status

      Route::group([
          'prefix' => 'roles'
      ], function () {

          Route::post('/assign/{user}', 'AdminController@assignRole');//not yet no users
          Route::post('/create', 'AdminController@createRole');//done
          Route::get('/lists', 'AdminController@listRole');//done
          Route::post('/delete', 'AdminController@deleteRole');//done
      });


    // /admin/file
    Route::group([
        'prefix' => 'file'
    ], function () {
        Route::get('/ot', 'AdminController@getFileOvertime');//done
        Route::get('/ob', 'AdminController@getFileOfficialBusiness');//done
        Route::get('/sc', 'AdminController@getFileScheduleChange');//done
        Route::get('/leave', 'AdminController@getFileLeave');//done
        Route::get('/timeinout', 'AdminController@getFileTimeinout');
        Route::get('/leavecancellation', 'AdminController@getFileLeaveCancellation');

        Route::put('/ot/{overtime}', 'AdminController@updateFileOvertime');//done
        Route::put('/ob/{officialBusiness}', 'AdminController@updateFileOfficialBusiness');//done
        Route::put('/sc/{schedule}', 'AdminController@updateFileScheduleChange');//done
        Route::put('/leave/{leave}', 'AdminController@updateFileLeave');//done
        Route::put('/timeinout/{inout}', 'AdminController@updateFileTimeinout');
        Route::put('/leavecancellation/{leaveCancel}', 'AdminController@updateFileLeaveCancellation');
    });

//----------------------------------------------------------------
    // /admin/employee/~

    Route::group([
        'prefix' => 'employee'
    ], function () {

    Route::get('', 'AdminController@employeeList');
    Route::get('/logs', 'AdminController@employeeLog');
    Route::get('/{id}', 'AdminController@getEmployee');
//----------------------------------------------------------------
    // /admin/employee/logs/~

     Route::group([
         'prefix' => 'logs'
     ], function () {
     Route::get('/onleave/{date}', 'AdminController@onLeaveLog');
     Route::get('/onleave', 'AdminController@onLeaveLog');

     Route::get('/office/{date}', 'AdminController@officeLog');
     Route::get('/office', 'AdminController@officeLog');

     Route::get('/onfield/{date}', 'AdminController@onfieldLog');
     Route::get('/onfield', 'AdminController@onfieldLog');

     Route::get('/absent/{date}', 'AdminController@absentLog');
     Route::get('/absent', 'AdminController@absentLog');
     Route::get('/absent/{employee_id/{date}}', 'AdminController@absentLog');
     Route::get('/absent/{employee_id}', 'AdminController@absentLog');

     Route::get('/late/{date}', 'AdminController@lateLog');
     Route::get('/late', 'AdminController@lateLog');
     Route::get('/late/{employee_id}', 'AdminController@lateLog');
     Route::get('/late/{employee_id}/{date}', 'AdminController@lateLog');

     Route::get('/leave/{date}', 'AdminController@leaveLog');
     Route::get('/leave', 'AdminController@leaveLog');
     Route::get('/leave/{employee_id}', 'AdminController@leaveLog');
     Route::get('/leave/{employee_id}/{date}', 'AdminController@leaveLog');

     Route::get('/{date}', 'AdminController@employeeLogDate');
     Route::get('/{date_from}/{date_to}', 'AdminController@employeeLogFromTo');
   });// /employee/logs/~ prefix
  });// employee?~ prefix
});//middleware
});// admin/~ prefix
//----------------------------------------------------------------

//
//
// });
//------------------------------------------------------------------------
// employee/~
Route::group([
    'prefix' => 'employee'
], function () {
      Route::post('register', 'EmployeeController@employeeRegister');


      Route::group([
        'middleware' => ['auth:api', 'role:employee']
       ], function(){
      Route::get('/logs/{id}','EmployeeController@employeeLogs');
      Route::get('/leave/{id}','EmployeeController@employeeLeave');
      Route::get('/late/{id}','EmployeeController@employeeLate');//same method with late/{id}/{date}
      Route::get('/late/{id}/{date}','EmployeeController@employeeLate');//same method with late/{id}

      Route::get('/inout/{attendance_id}', 'EmployeeController@inout');

      Route::post('/timein', 'EmployeeController@employeeTimeIn');//done needs testing
      Route::post('/timeout', 'EmployeeController@employeeTimeOut');//done needs testing
      // /employee/file/~ route
      Route::group([
          'prefix' => 'file'
      ], function () {
          Route::post('ot', 'EmployeeController@fileOverTime');//testing
          Route::post('ob', 'EmployeeController@fileOfficialBusiness');
          Route::post('sc', 'EmployeeController@fileScheduleChange');
          Route::post('leave', 'EmployeeController@fileLeave');
          Route::post('timeinout', 'EmployeeController@fileTimeinout');// request manual timein or mobile
          Route::post('leavecancellation/{requestId}', 'EmployeeController@FileLeaveCancellation');
      }); // employee/file/~
    });
}); // employee/~
//------------------------------------------------------------------------
