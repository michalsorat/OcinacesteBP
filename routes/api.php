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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//login from android devic
Route::Group(['namespace' => 'Api'], function(){
	Route::post('/loginAndroid', 'LoginController@login')->name('loginAndroid');
});

Route::post('/delete', 'ProblemController@deleteProblem')->name('deleteProblem');
Route::post('/uploadRiesenieImage', 'ProblemController@uploadRiesenieImage')->name('uploadRiesenieImage');
Route::post('/editProblem', 'ProblemController@editProblem')->name('editProblem');
