<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;

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
@include('admin.php');


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/login', 'App\Http\Controllers\Admin\LoginController@showLoginForm')->name('login');
    Route::post('/login', 'App\Http\Controllers\Admin\LoginController@login');
    Route::post('/logout', 'App\Http\Controllers\Admin\LoginController@logout')->name('logout');
    Route::get('/register', 'App\Http\Controllers\Admin\RegisterController@showRegistrationForm')->name('register');
    Route::post('/register', 'App\Http\Controllers\Admin\RegisterController@register');
});

Route::post('/logout', 'App\Http\Controllers\Admin\LoginController@logout')->name('logout');



Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('optimize:clear');  
    Artisan::call('clear-compiled');
   return "Cleared!";
});