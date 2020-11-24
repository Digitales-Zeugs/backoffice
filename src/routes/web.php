<?php

use Illuminate\Support\Facades\Route;
use App\Models\ProfileUpdates;

Route::get('/', 'DashboardController@index');

Route::get('/members', 'MembersController@index');
Route::get('/members/datatables', 'MembersController@datatables');
Route::get('/members/{profile}', 'MembersController@view');
Route::post('/members/{profile}/status', 'MembersController@changeStatus');

Route::get('/profiles', 'ProfilesController@index');
Route::get('/profiles/datatables', 'ProfilesController@datatables');
Route::get('/profiles/{profile}', 'ProfilesController@view');
Route::post('/profiles/{profile}/status', 'ProfilesController@changeStatus');

Route::get('/works', 'WorksController@index');
Route::get('/works/datatables', 'WorksController@datatables');
Route::get('/works/files', 'WorksController@downloadFile');

Route::get('/works/{registration}', 'WorksController@showView');
Route::post('/works/{registration}/status', 'WorksController@changeStatus');
Route::post('/works/{registration}/response', 'WorksController@response');
Route::post('/works/{registration}/observations', 'WorksController@saveObservations');

Route::get('/integration', 'IntegrationController@index');
Route::get('/integration/works', 'IntegrationController@exportWorks');
Route::post('/integration/works', 'IntegrationController@importWorks');

Route::get('/login', 'AuthController@login')->name('login');
Route::get('/logout', 'AuthController@logout')->name('logout');
Route::post('/auth', 'AuthController@auth');