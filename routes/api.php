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
Route::post('/users/password/{user}', 'UsersController@password');
Route::post('/users/store', 'UsersController@store');
Route::put('/users/update/{user}', 'UsersController@update');
Route::delete('/users/delete/{user}', 'UsersController@destroy');

Route::get('roles', 'RolesController@index');
Route::get('roles/info', 'RolesController@info');
Route::post('/roles/store', 'RolesController@store');
Route::put('/roles/update/{role}', 'RolesController@update');
Route::delete('/roles/delete/{role}', 'RolesController@destroy');

Route::get('policies', 'PoliciesController@index');
Route::get('policies/all', 'PoliciesController@all');
Route::get('policies/info', 'PoliciesController@info');
Route::post('/policies/store', 'PoliciesController@store');
Route::put('/policies/update/{policy}', 'PoliciesController@update');
Route::delete('/policies/delete/{policy}', 'PoliciesController@destroy');

Route::get('templates', 'TemplatesController@index');
Route::get('templates/all', 'TemplatesController@all');
Route::get('templates/info', 'TemplatesController@info');
Route::post('/templates/store', 'TemplatesController@store');
Route::put('/templates/update/{template}', 'TemplatesController@update');
Route::delete('/templates/delete/{template}', 'TemplatesController@destroy');