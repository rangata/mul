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

Route::get('pat/{id}', function ($id) {

    $cl = new \GuzzleHttp\Client();
    $resp = $cl->get('http://localhost:8042/instances/' . $id . '/preview');
//    $resp = $resp->getBody();

    $stream = GuzzleHttp\Psr7\stream_for($resp->getBody());
    return dd($stream->tell());
});