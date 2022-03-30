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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', 'ProblemController@welcomePage')->name('welcome');

Route::get('/zaregistrovanyObcan', 'ZaregistrovanyObcanController@index')->name('zaregistrovanyObcan');
Route::get('/unregisteredCitizen', 'NezaregistrovanyObcanController@index')->name('unregisteredCitizen');

Route::post('filtrovaneProblemy', 'ProblemController@filter')->name('filtered');
Route::post('/moje', 'ProblemController@priradeneProblemyDispecerovi')->name('problem.priradeneDispecerovi');


Route::get('/problem/priradeneDispecerovi', 'ProblemController@priradeneProblemyDispecerovi')->name('priradeneDispecerovi');
Route::get('/problem/{problem}/priradeniZamestnanci', 'ProblemController@priradeniZamestnanci')->name('priradeniZamestnanci');
Route::get('/problem/{problem}/priradeneVozidla', 'ProblemController@priradeneVozidla')->name('priradeneVozidla');
Route::get('/problem/{problem}/stavyRieseniaProblemu', 'ProblemController@stavyRieseniaProblemu')->name('stavyRieseniaProblemu');
Route::get('/problem/{problem}/popisyStavovRieseniaProblemu', 'ProblemController@popisyStavovRieseniaProblemu')->name('popisyStavovRieseniaProblemu');

Route::get('/mapa', 'ProblemController@mapa')->name('mapa');

Route::resource('problem', 'ProblemController');
Route::resource('pouzivatelia', 'UserController');

//Route::get('/welcomePage/create', 'ProblemController@welcomePageCreate')->name('welcomePage.create');
Route::post('/createProblem', 'ProblemController@createProblem')->name('createProblem');

Route::get('/download', function()
{
    $file = public_path()."/OciNaCesteAPKv1.1.apk";
    return Response::download($file);
}) ->name('download');

Route::get('/autocomplete', 'ProblemController@autocomplete')->name('autocomplete');

Route::get('/image/{id}', 'ProblemController@image');

Route::get('/userDetails/{id}', 'UserController@getUserDetails');

Route::get('/problemsJsonPagination', 'ProblemController@allProblemsJsonPagination');
Route::get('/problemsJson', 'ProblemController@allProblemsJson');
Route::get('/problemsJson/{id}', 'ProblemController@problemById');
Route::post('/createBump', 'ProblemController@storeBump');

Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::resource('admin', 'AdminController');
    Route::get('/autocompleteUser', 'AdminController@autocomplete')->name('autocompleteUser');
    Route::get('/filter', 'AdminController@filter')->name('adminFilter');
    Route::get('/updateCounts', 'AdminController@countProblemsOrUsers')->name('adminCounts');
    Route::get('/userRoleInfo/{id}', 'AdminController@userRoleInfo')->name('userRoleInfo');
    Route::delete('/deleteUser', 'AdminController@deleteUser')->name('deleteUser');
});

Route::middleware(['auth', 'isManager'])->group(function () {
    Route::resource('manager', 'ManagerController');
    Route::get('/manageGroupProblems/{id}', 'ManagerController@manageGroupProblems')->name('manageGroupProblems');
    Route::put('/assignProblemsToGroup', 'ManagerController@assignProblemsToGroup')->name('assignProblemsToGroup');
    Route::put('/removeProblemsFromGroup', 'ManagerController@removeProblemsFromGroup')->name('removeProblemsFromGroup');
    Route::get('/workingGroups', 'ManagerController@manageWorkingGroups')->name('manageWorkingGroups');
    Route::get('/workingGroupChart/{id}', 'ManagerController@workingGroupChart')->name('workingGroupChart');
    Route::get('/workingGroupDetail/{id}', 'ManagerController@workingGroupDetail')->name('workingGroupDetail');
    Route::put('/changeAssignedVehicle', 'ManagerController@changeAssignedVehicle')->name('changeAssignedVehicle');
    Route::put('/changeAssignedCategories/{id}', 'ManagerController@changeAssignedCategories')->name('changeAssignedCategories');
    Route::put('/removeGroupUsers/{id}', 'ManagerController@removeGroupUsers')->name('removeGroupUsers');
    Route::put('/addGroupUsers/{id}', 'ManagerController@addGroupUsers')->name('addGroupUsers');
    Route::post('/createVehicle', 'ManagerController@createVehicle')->name('createVehicle');
    Route::delete('/deleteWorkingGroup', 'ManagerController@deleteWorkingGroup')->name('deleteWorkingGroup');
    Route::delete('/deleteVehicle', 'ManagerController@deleteVehicle')->name('deleteVehicle');

});

Route::middleware(['auth', 'isWorker'])->group(function () {
    Route::resource('worker', 'WorkerController');
});

//appka
//Route::post('/uploadProblemImage', 'ProblemController@storeProblemImgAndroid')->name('uploadProblemImage');
//Route::get('/showAllAndroid/{x}/{zamestnanec}/{stavProblemu}/{kategoria}/{datumOd}/{datumDo}/{vozidlo}/{priorita}/{stavRiesenia}/{y}', 'ProblemController@showAllProblemsAndroid')->name('showAllAndroid');
//Route::get('/unregisteredPostAndroid/{poloha}/{popis_problemu}/{kategoria_problemu}/{stav_problemu}/{imgId}/{idOfUser}', 'ProblemController@unregisteredAddRecordAndroid')->name('unregisteredPostAndroid');
//Route::post('/loginAndroid', 'Api/LoginController@login')->name('loginAndroid');
//Route::get('/spinners', 'ProblemController@getSpinnersAndroid')->name('spinners');
//Route::get('/downloadImg/{id}', 'ProblemController@getImgsAndroid')->name('downloadImg');
//Route::get('/history/{attribute}/{problemID}/{role}', 'ProblemController@historyAndroid')->name('history');
//Route::get('/showUsersAndroid', 'ProblemController@showUsersAndroid')->name('showUsersAndroid');
?>
