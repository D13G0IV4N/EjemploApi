<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/api/registro','App\Http\Controllers\UserController@register');
Route::post('/api/acceso','App\Http\Controllers\UserController@login');
Route::resource('/api/carros', 'App\Http\Controllers\CarController');
