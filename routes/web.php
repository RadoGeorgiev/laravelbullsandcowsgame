<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/game', 'App\Http\Controllers\HomeController@index');

Route::get('/newgame', 'App\Http\Controllers\HomeController@newGame');

Route::post('/checkgame', 'App\Http\Controllers\HomeController@checkGame');

