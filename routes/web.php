<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Controllers\UserController;

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

Route::get('/verify/user/{user}', [UserController::class, 'verify'])->name('user.verify');
Route::get('/intro','LandingpageController@index');
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index')->name('home');
Route::post('/install/check-db', 'HomeController@checkConnectDatabase');

// Whatsapp message
Route::get('/whatsapp/config', 'WhatsappController@config')->name('whatsapp.config');
Route::put('/whatsapp/update', 'WhatsappController@update')->name('whatsapp.update');

// Social Login
Route::get('social-login/{provider}', 'Auth\LoginController@socialLogin');
Route::get('social-callback/{provider}', 'Auth\LoginController@socialCallBack');


// Mobile Navbar routes
Route::get('/mobile-navbar', 'MobileNavController@index')->middleware(['auth', 'dashboard'])->name('mobile.navbar');
Route::post('/mobile-navbar', 'MobileNavController@store')->middleware(['auth', 'dashboard'])->name('mobile.navbar.store');
Route::put('/mobile-navbar/{icon}', 'MobileNavController@update')->middleware(['auth', 'dashboard'])->name('mobile.navbar.update');
Route::delete('/mobile-navbar/delete/{item}', 'MobileNavController@destroy')->middleware(['auth', 'dashboard'])->name('mobile.navbar.destroy');
Route::put('/hide/show/navbar', 'HideShowNavController@toggle')->middleware(['auth', 'dashboard'])->name('mobile.navbar.hide.show');

// Logs
Route::get(config('admin.admin_route_prefix').'/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware(['auth', 'dashboard','system_log_view'])->name('admin.logs');

Route::get('/install','InstallerController@redirectToRequirement')->name('LaravelInstaller::welcome');
Route::get('/install/environment','InstallerController@redirectToWizard')->name('LaravelInstaller::environment');

// Mobile app redirect https://travent.ae/user/messages
Route::redirect('/user/messages', '/');
