<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    //
    public function login()
    {
        $client = DB::table('oauth_clients')
            ->where('password_client', true)
            ->first();

        $data = [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => request('username'),
            'password' => request('password'),
        ];

        $request = Request::create('/oauth/token', 'POST', $data);
        return app()->handle($request);
    }
    public function register()
    {
        User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password'))
        ]);

        return response()->json(['status' => 201]);
    }

}
