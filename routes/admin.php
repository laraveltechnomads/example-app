<?php

use Illuminate\Support\Facades\Route;

Route::get('dashboard', function () { 
    return view('admin.dashboard.index');
})->name('dashboard');



Route::get('/', function () {
    return view('welcome');
});
