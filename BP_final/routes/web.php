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

Route::get('/', 'ProblemController@welcomePage');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/zaregistrovanyObcan', 'ZaregistrovanyObcanController@index')->name('zaregistrovanyObcan');
Route::get('/nezaregistrovanyObcan', 'NezaregistrovanyObcanController@index')->name('nezaregistrovanyObcan');
Route::get('/admin', 'AdminController@index')->name('admin');
Route::get('/dispecer', 'DispecerController@index')->name('dispecer');
Route::get('/manazer', 'ManazerController@index')->name('manazer');

Route::resource('problem', 'ProblemController');
Route::get('/mapa', 'ProblemController@mapa');




