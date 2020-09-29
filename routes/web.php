<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'DashboardController@index');

Route::get('/login', 'AuthController@login')->name('login');
