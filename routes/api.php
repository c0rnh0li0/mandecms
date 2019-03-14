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

Route::get('pages', 'PagesController@index');
Route::get('pages/all', 'PagesController@all');
Route::post('/pages/store', 'PagesController@store');
Route::put('/pages/update/{page}', 'PagesController@update');
Route::delete('/pages/delete/{page}', 'PagesController@destroy');

Route::get('categories', 'CategoriesController@index');
Route::get('categories/all', 'CategoriesController@all');
Route::get('categories/info', 'CategoriesController@info');
Route::post('/categories/store', 'CategoriesController@store');
Route::put('/categories/update/{category}', 'CategoriesController@update');
Route::delete('/categories/delete/{category}', 'CategoriesController@destroy');

Route::get('menus', 'MenusController@index');
Route::get('menus/all', 'MenusController@all');
Route::get('menus/build', 'MenusController@build');
Route::post('menus/sort', 'MenusController@sort');
Route::get('menus/info', 'MenusController@info');
Route::post('/menus/store', 'MenusController@store');
Route::put('/menus/update/{menu}', 'MenusController@update');
Route::delete('/menus/delete/{menu}', 'MenusController@destroy');

Route::get('settings', 'SettingsController@index');
Route::post('/settings/store', 'SettingsController@store');
Route::put('/settings/update/{setting}', 'SettingsController@update');

Route::get('tags', 'TagsController@index');

Route::get('cms/slug/{any}', 'CMSController@slug')->where('any', '.*');