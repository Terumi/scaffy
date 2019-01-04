<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});








# Scaffy_Routes_Start
Route::group(["prefix" => "admin"], function () {
    Route::get('user/index', 'UserController@index');
Route::get('user/create', 'UserController@create');
Route::post('user/store', 'UserController@store');
Route::get('user/{id}/edit', 'UserController@edit');
Route::post('user/update', 'UserController@update');
Route::post('user/delete', 'UserController@delete');
Route::get('user_type/index', 'UserTypeController@index');
Route::get('user_type/create', 'UserTypeController@create');
Route::post('user_type/store', 'UserTypeController@store');
Route::get('user_type/{id}/edit', 'UserTypeController@edit');
Route::post('user_type/update', 'UserTypeController@update');
Route::post('user_type/delete', 'UserTypeController@delete');
#routes#
});
# Scaffy_Routes_End
