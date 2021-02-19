<?php

use App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'auth'], function () {
    Route::get('login', [Controllers\Auth\TypeController::class, 'login'])->name('login-type');
    Route::get('logout', [Controllers\Auth\TypeController::class, 'logout'])->name('logout-type');

    Route::group(['prefix' => 'local'], function () {
        Auth::routes(['register' => false]); // @TODO disabled for now, until it gets set up properly for non-NU sponsors
    });

    Route::group(['prefix' => 'sso'], function () {
        Route::get('login', [Controllers\Auth\WebSSOController::class, 'login'])->name('login-sso');
        Route::get('logout', [Controllers\Auth\WebSSOController::class, 'logout'])->name('logout-sso');
    });
});
