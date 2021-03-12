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

Route::get('/', [Controllers\HomeController::class, 'index'])->name('home');

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

/**
 * The org/prog can be inferred from the cycle ID, but this URL is part of the UI,
 * so we want it to be a nice SEO-y URL.
 *
 * Program admins will be paying close attention to it when they post it on their
 * site or send it out in their newsletters. That's why it gets to be special.
 */
Route::get('apply/{organization:slug}/{program:slug}/{cycle}', Controllers\Applicant\DisplayFormController::class)->name('application-form');
Route::get('apply', Controllers\Applicant\DiscoverController::class)->name('application-discover');

Route::group(['prefix' => 'applicant'], function () {
    Route::resource('submission', Controllers\Applicant\SubmissionController::class, ['as' => 'applicant', 'only' => ['create', 'store']]);
});

Route::group(['prefix' => 'admin'], function () {
    Route::resource('organization', Controllers\Admin\OrganizationController::class, ['only' => ['index', 'show', 'create', 'store']]);
    Route::resource('program', Controllers\Admin\ProgramController::class, ['only' => ['index', 'show', 'create', 'store']]);
    Route::resource('form', Controllers\Admin\FormController::class, ['except' => ['destroy', 'index', 'show']]);
    Route::resource('cycle', Controllers\Admin\CycleController::class, ['only' => ['create', 'store']]);
});
