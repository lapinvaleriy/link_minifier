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

Route::get('/', 'LinkController@show');

Route::post('/minify', 'LinkController@create');
Route::post('/login', 'UserController@login');
Route::get('/logout', 'UserController@logout');
Route::post('/register', 'UserController@create');
