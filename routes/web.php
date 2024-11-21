<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('employee.sign_in');
});


Route::controller(EmployeeController::class)->group(function () {
    Route::get('sign-up', 'showSignUpForm')->name('sign-up');
    Route::post('sign-up', 'signUp')->name('sign-up');
    Route::get('sign-in', 'showLoginForm')->name('sign-in');
    Route::post('sign-in', 'SignIn')->name('sign-in');
    Route::get('employee-info', 'employeeInfo')->name('employee-info');
    Route::post('dashboard', 'SignIn')->name('dashboard');
});

Route::controller(DashboardController::class)->group(function () {
    Route::get('dashboard', 'dashboard')->name('dashboard');
    Route::post('destroy/{id}', 'delete')->name('destroy');
    Route::get('edit/{id}', 'edit')->name('edit');
    Route::post('update/{id}', 'update')->name('update');
    Route::get('view/{id}', 'view')->name('view');
    Route::get('logout', 'logout')->name('logout');
});
