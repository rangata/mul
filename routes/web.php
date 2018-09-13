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

Route::get('/', function () {
    return view('layouts.main');
});

Route::prefix('pub')->group(function () {

    Route::get('/', function () {
        return 'You are on public area!';
    });
    Route::get('/{hash}', function ($hash){
       return $hash;

    });

});
Route::resource('res', 'ResourceController');
Route::get('res/{id}/studio/{inid}', function ($id, $inid) {

    $imageId = $inid;
    return view('patients.studio', compact('id','imageId'));
});

Route::get('bs', function () {

    return bcrypt("asd");

});
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});
