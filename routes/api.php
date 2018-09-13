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
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});
Route::get('/asd', function () {


    $cl = new \GuzzleHttp\Client();
    $resp = $cl->get('http://192.168.10.10:8042/patients');
    $resp = $resp->getBody();


    return $resp;

});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('pat/{id}', function ($id) {

    $cl = new \GuzzleHttp\Client();
    $resp = $cl->get(env('DCM_SERVER') . '/instances/' . $id . '/preview');
    $resp = $resp->getBody();


    return $resp;
});

Route::get('/instances/{id}/preview', function ($id) {

    $cl = new \GuzzleHttp\Client();
    $respo = $cl->get(env('DCM_SERVER') . '/instances/' . $id . '/preview');

    $respo = $respo->getBody()->getContents();
//    $respo = "data:image/png;base64," . base64_encode($respo);

    return $respo;

});
Route::get('/instances/{id}/previewEncode', function ($id) {

    $cl = new \GuzzleHttp\Client();
    $respo = $cl->get(env('DCM_SERVER') . '/instances/' . $id . '/preview');

    $respo = $respo->getBody()->getContents();
    $respo = "data:image/png;base64," . base64_decode($respo);

    return $respo;

});

Route::prefix('patients')->group(function () {

//    Route::middleware('auth:api')->group(function () {


    Route::get('/', function () {

//        $resources = \App\Resource::with('tags')->where('resourceType', '=', 0)->get();
          $resources = \App\Resource::with('tags')->where('resourceType','=',0)->get();

//        dd();
          return response()->json(new \App\Http\Resources\ResCollection($resources));
    });
    Route::get('/{id}', function ($id) {

        $client = new \GuzzleHttp\Client();
        $responseFromDicomServer = $client->get(env('DCM_SERVER') . '/patients/' . $id);
//        TODO should be described
        $sts = collect(json_decode($responseFromDicomServer->getBody()->getContents(), true));

        return response()->json($sts);

    });

    Route::get('/{id}/studies', function ($id) {

        $client = new \GuzzleHttp\Client();
        $responseFromDicomServer = $client->get(env('DCM_SERVER') . '/patients/' . $id . '/studies');
//        TODO should be described
        $sts = collect(json_decode($responseFromDicomServer->getBody()->getContents(), true));

        return response()->json($sts);

    });
});

Route::prefix('studies')->group(function () {

    Route::get('/', function () {
        $client = new \GuzzleHttp\Client();
        $responseFromDicomServer = $client->get(env('DCM_SERVER') . '/studies');
//        TODO should be described
        $sts = collect(json_decode($responseFromDicomServer->getBody()->getContents(), true));

        return response()->json($sts);
    });


   Route::get('/{id}', function ($id) {
        $client = new \GuzzleHttp\Client();
        $responseFromDicomServer = $client->get(env('DCM_SERVER') . '/studies/' . $id);
//        TODO should be described
        $sts = collect(json_decode($responseFromDicomServer->getBody()->getContents(), true));

        return response()->json($sts);
    });

   Route::get('/{id}/series', function ($id) {
        $client = new \GuzzleHttp\Client();
        $responseFromDicomServer = $client->get(env('DCM_SERVER') . '/studies/' . $id .'/series');
//        TODO should be described
        $sts = collect(json_decode($responseFromDicomServer->getBody()->getContents(), true));

        return response()->json($sts);
    });
});
Route::get('/instances/{id}', function ($id) {

    $client = new \GuzzleHttp\Client();
    $responseFromDicomServer = $client->get(env('DCM_SERVER') . '/instances/' . $id );
//        TODO should be described
    $sts = collect(json_decode($responseFromDicomServer->getBody()->getContents(), true));

    return response()->json($sts);


});

Route::get('/series/{id}', function ($id) {

    $client = new \GuzzleHttp\Client();
    $responseFromDicomServer = $client->get(env('DCM_SERVER') . '/series/' . $id );
//        TODO should be described
    $sts = collect(json_decode($responseFromDicomServer->getBody()->getContents(), true));

    return response()->json($sts);


});

Route::get('/studies/{id}', function ($id) {

    $client = new \GuzzleHttp\Client();
    $responseFromDicomServer = $client->get(env('DCM_SERVER') . '/studies/' . $id );
//        TODO should be described
    $sts = collect(json_decode($responseFromDicomServer->getBody()->getContents(), true));

    return response()->json($sts);


});

Route::get('/studies/{id}/instances', function ($id) {

    $client = new \GuzzleHttp\Client();
    $responseFromDicomServer = $client->get(env('DCM_SERVER') . '/studies/' . $id .'/instances' );
//        TODO should be described
    $sts = collect(json_decode($responseFromDicomServer->getBody(), true));

    return response()->json($sts);


});
Route::get('/studies/{id}/get', function ($id) {

    $client = new \GuzzleHttp\Client();
    $responseFromDicomServer = $client->get(env('DCM_SERVER') . '/studies/' . $id .'/instances' );
//        TODO should be described
    $sts = collect(json_decode($responseFromDicomServer->getBody(), true));

    return response()->json($sts);


});

Route::get('/gpp/{id}', function ($id){
    $client = new \GuzzleHttp\Client();
    $responseFromDicomServer = $client->get(env('DCM_SERVER') . '/instances/' . $id .'/preview' );
//        TODO should be described

    $sts = $responseFromDicomServer->getBody()->getContents();

    dd($sts);
});

