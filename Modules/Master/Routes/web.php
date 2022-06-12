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

Route::prefix('master')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/', 'MasterController@index');

        Route::prefix('/menu')->group(function () {
            Route::get('/', 'MenuController@index')->name('master-menu');
            Route::post('/datatable', 'MenuController@datatable')->name('master-menu-datatable');
        });

        Route::prefix('/role')->group(function () {
            Route::get('/', 'RoleController@index')->name('master-role');
        });
    });;
