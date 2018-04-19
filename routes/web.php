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
Auth::routes();
Route::match(['get', 'post'], 'register', function(){
    return redirect('/');
});

// Routes for users (employees)
Route::get('/', 'HomeController@updateExpiredAlus')->name('home');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/changepassword', 'ChangePasswordsController@change');
Route::post('/changepassword', 'ChangePasswordsController@store');

Route::get('/aluforms', 'AluFormsController@index');
Route::post('/aluforms', 'AluFormsController@selectRange');
Route::get('/aluforms/display/{month}', 'AluFormsController@indexWithRange');
Route::get('/aluform/{params}', 'AluFormsController@show');
Route::get('/aluform/{params}/edit', 'AluFormsController@edit');
Route::put('/aluform/{params}/edit', 'AluFormsController@update');
Route::get('/aluforms/create', 'AluFormsController@create');
Route::post('/aluforms/create', 'AluFormsController@storeAdvanced');
Route::get('/aluforms/file/{params}', 'AluFormsController@file');
Route::post('/aluforms/file', 'AluFormsController@store');
Route::get('/aluforms/expired', 'AluFormsController@expiredAlus');

Route::get('/loaforms', 'LoaFormsController@index');
Route::post('/loaforms', 'LoaFormsController@selectRange');
Route::get('/loaforms/display/{month}', 'LoaFormsController@indexWithRange');
Route::get('/loaforms/create', 'LoaFormsController@create');
Route::post('/loaforms/create', 'LoaFormsController@store');
Route::get('/loaform/{params}', 'LoaFormsController@show');

Route::get('/attendancerecord', 'UserAttendanceRecordController@index');
Route::post('/attendancerecord', 'UserAttendanceRecordController@selectRange');
Route::get('/attendancerecord/{month}', 'UserAttendanceRecordController@indexWithRange');
Route::get('/attendancerecord/{month}/logs', 'UserAttendanceRecordController@showLogs');

// Routes for users (supervisors)
Route::get('/users', 'SupervisorController@usersIndex');

Route::get('/aluform/{params}/decision', 'SupervisorController@aluFormRecommendation');
Route::put('/aluform/{params}/decision', 'SupervisorController@aluFormUpdate');

Route::get('/loaform/{params}/decision', 'SupervisorController@loaFormRecommendation');
Route::put('/loaform/{params}/decision', 'SupervisorController@loaFormUpdate');


// Routes for admin
Route::get('/admin/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
Route::post('/admin/login', 'Auth\AdminLoginController@Login')->name('admin.login.submit');

Route::get('/admin', 'AdminController@index')->name('admin.dashboard');

Route::get('/admin/aluforms', 'AdminAluFormsController@index');
Route::post('/admin/aluforms', 'AdminAluFormsController@selectRange');
Route::get('/admin/aluforms/display/{month}', 'AdminAluFormsController@indexWithRange');
Route::get('/admin/aluforms/pending', 'AdminAluFormsController@pendingAluForms');
Route::get('/admin/aluform/{params}', 'AdminAluFormsController@show');
Route::get('/admin/aluform/{params}/decision', 'AdminAluFormsController@aluFormDecision');
Route::put('/admin/aluform/{params}/decision', 'AdminAluFormsController@aluFormUpdate');

Route::get('/admin/loaforms', 'AdminLoaFormsController@index');
Route::post('/admin/loaforms', 'AdminLoaFormsController@selectRange');
Route::get('/admin/loaforms/display/{month}', 'AdminLoaFormsController@indexWithRange');
Route::get('/admin/loaforms/pending', 'AdminLoaFormsController@pendingLoaForms');
Route::get('/admin/loaform/{params}', 'AdminLoaFormsController@show');
Route::get('/admin/loaform/{params}/decision', 'AdminLoaFormsController@loaFormDecision');
Route::put('/admin/loaform/{params}/decision', 'AdminLoaFormsController@loaFormUpdate');

Route::get('/admin/alus/export', 'ExportController@index');
Route::post('/admin/alus/export', 'ExportController@selectRange');
Route::get('/admin/alus/export/{params}', 'ExportController@exportAlus');

Route::resource('/admin/users', 'UsersController');

Route::get('/admin/timekeeping/', 'TimekeepingController@index');
Route::post('/admin/timekeeping/', 'TimekeepingController@store');
Route::get('/admin/timekeeping/delete', 'TimekeepingController@deleteIndex');
Route::post('/admin/timekeeping/delete', 'TimekeepingController@selectRange');
Route::get('admin/timekeeping/delete/{params}', 'TimekeepingController@destroy');

Route::get('/admin/attendancerecords/u={uniqid}', 'AttendanceRecordsController@updateExpiredAlus');
Route::get('/admin/attendancerecords', 'AttendanceRecordsController@index');
Route::post('/admin/attendancerecords', 'AttendanceRecordsController@selectRange');
Route::get('/admin/attendancerecords/{month}', 'AttendanceRecordsController@indexWithRange');
Route::get('/admin/attendancerecords/{month}/{employee_num}', 'AttendanceRecordsController@show');
Route::get('admin/attendancerecords/{month}/{employee_num}/logs', 'AttendanceRecordsController@showLogs');