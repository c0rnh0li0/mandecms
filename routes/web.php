<?php

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

//Auth::routes();

Route::get('/dashboard', 'DashboardController@dashboard'); //->where('any', '.*');
Route::get('/dashboard/{any}', 'DashboardController@dashboard')->where('any', '.*');
Route::get('/{any}', 'DashboardController@index')->where('any', '.*');


//Route::get('/dashboard', 'DashboardController@index');
