<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::namespace('App\Http\Controllers')->group(function(){
    
    
});

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::namespace("App\Http\Controllers\Admin")->prefix('admin')->group(function(){
    
    Route::get('/dashboard', 'DashboardController@index')->name('admin.home');

    Route::resource('/category','CategoryController');


    Route::namespace('Auth')->group(function(){
        Route::get('/login','LoginController@showloginform')->name('admin.login');
        Route::post('/login','LoginController@login');
        Route::post('logout','LoginController@logout')->name('admin.logout');

    });
        
});

//stop