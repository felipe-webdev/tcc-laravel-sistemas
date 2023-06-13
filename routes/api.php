<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//   return $request->user();
// });

Route::middleware('auth:sanctum')->group(function ()
{
  Route::namespace('App\Http\Controllers')->group(function ()
  {
    Route::post('/logout', 'AuthController@logout');
  });

  Route::namespace('App\Http\Controllers\Api')->group(function ()
  {
    Route::post('/insertEmployee', 'CreateController@insertEmployee');
    Route::post('/insertFamily',   'CreateController@insertFamily');
    Route::post('/insertUser',     'CreateController@insertUser');
    Route::post('/insertEmail',    'CreateController@insertEmail');
    Route::post('/insertPhone',    'CreateController@insertPhone');
    Route::post('/insertDepart',   'CreateController@insertDepart');
    Route::post('/insertJob',      'CreateController@insertJob');
    Route::post('/insertImage',    'CreateController@insertImage');

    Route::get('/getSessionUser',   'ReadController@getSessionUser');
    Route::get('/getSystemTypes',   'ReadController@getSystemTypes');
    Route::get('/countRecords',     'ReadController@countRecords');
    Route::post('/isUserAvailable', 'ReadController@isUserAvailable');
    Route::post('/listEmployees',   'ReadController@listEmployees');
    Route::post('/listJobs',        'ReadController@listJobs');
    Route::post('/listDeparts',     'ReadController@listDeparts');
    Route::post('/getEmployee',     'ReadController@getEmployee');
    Route::post('/getImage',        'ReadController@getImage');

    Route::post('/alterPass',      'UpdateController@alterPass');
    Route::post('/resetPass',      'UpdateController@resetPass');
    Route::post('/updateFamily',   'UpdateController@updateFamily');
    Route::post('/updateEmail',    'UpdateController@updateEmail');
    Route::post('/updatePhone',    'UpdateController@updatePhone');
    Route::post('/updateEmployee', 'UpdateController@updateEmployee');
    Route::post('/updateJob',      'UpdateController@updateJob');
    Route::post('/updateDepart',   'UpdateController@updateDepart');

    Route::post('/deleteFamily', 'DeleteController@deleteFamily');
    Route::post('/deleteEmail',  'DeleteController@deleteEmail');
    Route::post('/deletePhone',  'DeleteController@deletePhone');
  });
});