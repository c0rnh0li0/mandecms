<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login')->name('login');
    Route::post('signup', 'AuthController@signup')->name('register');

    Route::group([
        'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'AuthController@logout')->name('logout');
        Route::get('user', 'AuthController@user')->name('user');
    });
});


Route::get('users', 'UsersController@index');
Route::get('users/info', 'UsersController@info');
Route::get('users/userslist', 'UsersController@userslist');
Route::get('users/create', 'UsersController@create');
Route::get('users/edit', 'UsersController@edit');
Route::post('users/store', 'UsersController@store');
Route::put('users/update', 'UsersController@update');

Route::get('roles', 'RolesController@index');
/*Route::get('users/info', 'UsersController@info');
Route::get('users/userslist', 'UsersController@userslist');
Route::get('users/create', 'UsersController@create');
Route::get('users/edit', 'UsersController@edit');
Route::post('users/store', 'UsersController@store');
Route::put('users/update', 'UsersController@update');*/


