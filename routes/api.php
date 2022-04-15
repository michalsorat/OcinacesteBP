<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JWTController;
use App\Http\Controllers\MobileAppProblemController;

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
Route::post('/comment', 'ProblemController@comment')->name('comment');
Route::post('/deleteAccount', 'ProblemController@deleteAccountAndroid')->name('deleteAccount');
Route::post('/editAccount', 'ProblemController@editAccountAndroid')->name('editAccount');

Route::group(['middleware' => 'api'], function($router) {
    Route::post('/register', [JWTController::class, 'register']);
    Route::post('/login', [JWTController::class, 'login']);
    Route::post('/logout', [JWTController::class, 'logout']);
    Route::post('/refresh', [JWTController::class, 'refresh']);
    Route::post('/profile', [JWTController::class, 'profile']);
    Route::get('/myProblems', [MobileAppProblemController::class, 'myProblems']);
    Route::post('/storeBump', [MobileAppProblemController::class, 'storeBump']);
    Route::post('/createProblem', [MobileAppProblemController::class, 'createProblem']);
});
