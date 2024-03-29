<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
  return redirect('/login');
});

Route::get('/home', function () {
  return redirect('/app');
});

Route::middleware('guest')->group(function () {
  Route::get('/login', function () {
    return view('login');
  })->name('login');

  Route::post('/login', 'App\Http\Controllers\AuthController@login');
});

Route::middleware('auth:sanctum')->get('/app', function () {
  return view('app');
});