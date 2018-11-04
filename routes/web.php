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

Route::get('/', 'LinkController@show')->name('main');
Route::get('/stat', 'StatisticsController@show');
Route::get('/stat/{url}', 'StatisticsController@info');
Route::get('/get_data', 'StatisticsController@getData');
Route::get('{url}', 'LinkController@redirect');
Route::post('/minify', 'LinkController@create');
