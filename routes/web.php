<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\CommonController;

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});


Route::namespace("App\Http\Controllers\Admin")->prefix('admin')->group(function () {

    Route::middleware('admin')->group(function () {

        Route::get('/dashboard', 'DashboardController@index')->name('admin.home');

        Route::resource('/category', 'CategoryController');
        Route::resource('/attributes', 'AttributesController');
        Route::resource('/products', 'ProductController');
        Route::post('/get-product-details', 'ProductController@getProductDetails')->name('get-product-details');

        Route::resource('roles','RoleController');
        Route::get('/roles/{roleId}/give-permissions','RoleController@addPermissionToRole');
        Route::put('/roles/{roleId}/give-permissions','RoleController@givePermissionToRole');
        Route::get('admin/roles/{role}', 'RoleController@show')->name('roles.show');

        Route::get('/orders', [OrderController::class, 'index'])->name('order.index');
        Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('order.edit');
        Route::put('/admin/orders/{order}/status/{status}', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

        Route::resource('permissions', 'PermissionController');
        Route::put('/permissions/{id}', [PermissionController::class, 'update'])->name('permissions.update');

        Route::resource('users','UserController');
        Route::get('admin/users/{user}', 'UserController@show')->name('users.show');
        Route::get('admin/users/{user}', 'UserController@edit')->name('users.edit');
        Route::delete('admin/users/{user}', 'UserController@destroy')->name('users.destroy');

    });

    Route::namespace('Auth')->group(function () {
        Route::get('/login', 'LoginController@showloginform')->name('admin.login');
        Route::post('/login', 'LoginController@login');
        Route::post('/logout', 'LoginController@loggedout')->name('admin.logout');
    });
});

//stop