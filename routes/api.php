<?php

use Illuminate\Http\Request;
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

Route::namespace('App\Http\Controllers')->middleware('auth:sanctum')->group(function ()
{
  Route::post('/logout', 'AuthController@logout');
});

Route::namespace('App\Http\Controllers\Api')->middleware('auth:sanctum')->group(function ()
{
  // Route::post('/login', 'ApiController@login');
  // Route::post('/logout', 'ApiController@logout');
  Route::get('/getSessionUser', 'ApiController@getSessionUser');
  Route::post('/alterPass', 'ApiController@alterPass');
  Route::post('/resetPass', 'ApiController@resetPass');
  Route::get('/getSystemTypes', 'ApiController@getSystemTypes');
  Route::get('/countRecords', 'ApiController@countRecords');
  Route::post('/insertEmployee', 'ApiController@insertEmployee');
  Route::post('/insertFamily', 'ApiController@insertFamily');
  Route::post('/updateFamily', 'ApiController@updateFamily');
  Route::post('/deleteFamily', 'ApiController@deleteFamily');
  Route::post('/insertUser', 'ApiController@insertUser');
  Route::post('/isUserAvailable', 'ApiController@isUserAvailable');
  Route::post('/listEmployees', 'ApiController@listEmployees');
  Route::post('/getEmployee', 'ApiController@getEmployee');
  Route::post('/insertEmail', 'ApiController@insertEmail');
  Route::post('/insertPhone', 'ApiController@insertPhone');
  Route::post('/deleteEmail', 'ApiController@deleteEmail');
  Route::post('/deletePhone', 'ApiController@deletePhone');
  Route::post('/updateEmail', 'ApiController@updateEmail');
  Route::post('/updatePhone', 'ApiController@updatePhone');
  Route::post('/updateEmployee', 'ApiController@updateEmployee');
  Route::post('/listJobs', 'ApiController@listJobs');
  Route::post('/listDeparts', 'ApiController@listDeparts');
  Route::post('/updateDepart', 'ApiController@updateDepart');
  Route::post('/updateJob', 'ApiController@updateJob');
});