<?php

use App\Http\Controllers\{
    DashboardController,
    SettingController,
    UserController,
};
use Illuminate\Support\Facades\Route;

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
    return redirect()->route('login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['middleware' => 'role:user'], function () {
        Route::get('/user', [UserController::class, 'index'])->name('user.index');
        Route::get('/user/roledata/{role}', [UserController::class, 'roledata'])->name('user.roledata');
        Route::get('/user/driver/data', [UserController::class, 'driverdata'])->name('user.datadriver');
        Route::post('/user/order/{id}', [UserController::class, 'order'])->name('user.order');
    });    

    Route::group(['middleware' => 'role:driver'], function () {

    });

    Route::group(['middleware' => 'level:1'], function () {

    });
 
    Route::group(['middleware' => 'level:1,2'], function () {

    });
});