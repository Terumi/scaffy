<?php
/*
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
###start
Route::group(["prefix" => "admin"], function () {
    #routes#
    Route::get('usertype/index', 'UserTypeController@index');
    Route::get('usertype/create', 'UserTypeController@create');
    Route::post('usertype/store', 'UserTypeController@store');
    Route::get('usertype/{id}/edit', 'UserTypeController@edit');
    Route::post('usertype/update', 'UserTypeController@update');
    Route::post('usertype/delete', 'UserTypeController@delete');

    Route::get('user/index', 'UserController@index');
    Route::get('user/create', 'UserController@create');
    Route::post('user/store', 'UserController@store');
    Route::get('user/{id}/edit', 'UserController@edit');
    Route::post('user/update', 'UserController@update');
    Route::post('user/delete', 'UserController@delete');

    Route::get('department/index', 'DepartmentController@index');
    Route::get('department/create', 'DepartmentController@create');
    Route::post('department/store', 'DepartmentController@store');
    Route::get('department/{id}/edit', 'DepartmentController@edit');
    Route::post('department/update', 'DepartmentController@update');
    Route::post('department/delete', 'DepartmentController@delete');

    Route::get('company/index', 'CompanyController@index');
    Route::get('company/create', 'CompanyController@create');
    Route::post('company/store', 'CompanyController@store');
    Route::get('company/{id}/edit', 'CompanyController@edit');
    Route::post('company/update', 'CompanyController@update');
    Route::post('company/delete', 'CompanyController@delete');

});
###end
