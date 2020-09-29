<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'DashboardController@index');

Route::get('/login', 'AuthController@login')->name('login');
Route::get('/logout', 'AuthController@logout')->name('logout');
Route::post('/auth', 'AuthController@auth');