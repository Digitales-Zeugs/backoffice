<?php

use Illuminate\Support\Facades\Route;
use App\Models\ProfileUpdates;

Route::get('/', 'DashboardController@index');

Route::get('/profiles', 'ProfilesController@index');
Route::get('/profiles/{profile}', 'ProfilesController@view');
Route::post('/profiles/{profile}/status', 'ProfilesController@changeStatus');

Route::get('/login', 'AuthController@login')->name('login');
Route::get('/logout', 'AuthController@logout')->name('logout');
Route::post('/auth', 'AuthController@auth');