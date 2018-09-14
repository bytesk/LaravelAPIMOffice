<?php


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


// Auth for Employee
Route::post('session/employee-login', 'Auth\AuthController@employeeLogin');
Route::post('session/employee-signup', 'Auth\AuthController@employeeSignUp');

//Get employee
Route::get('getEmployee', 'Auth\AuthController@index');


//post data test
Route::post('addUserTest', 'Auth\AuthController@storeTest');

// get employee profile
Route::get('session/user/profile' , 'Auth\AuthController@employeeProfile');