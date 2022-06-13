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

use Illuminate\Support\Facades\Route;

Route::prefix('system')
    ->middleware(['auth', 'role'])
    ->group(function () {
        Route::prefix('/menu')->group(function () {
            Route::get('/', 'MenuController@index')->name('system-menu');
            Route::post('/datatable', 'MenuController@datatable')->name('system-menu-datatable');
        });

        Route::prefix('/role')->group(function () {
            Route::get('/', 'RoleController@index')->name('system-role');
            Route::post('/datatable', 'RoleController@datatable')->name('system-role-datatable');
            Route::post('/store', 'RoleController@store')->name('system-role-store');
            Route::delete('/remove', 'RoleController@destroy')->name('system-role-remove');
        });

        Route::prefix('/feature')->group(function () {
            Route::get('/', 'FeatureController@index')->name('system-feature');
            Route::post('/datatable', 'FeatureController@datatable')->name('system-feature-datatable');
            Route::post('/store', 'FeatureController@store')->name('system-feature-store');
            Route::delete('/remove', 'FeatureController@destroy')->name('system-feature-remove');
        });
    });
