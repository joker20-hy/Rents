<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function main(Request $request)
    {
        $validator = $request->validation($request);
        if ($validator->fails()) {
            return response()->json('', 422);
        }
        $http = new \GuzzleHttp\Client([
            'base_uri' => 'http://192.168.0.200:8000',
            'headers'  => ['Accept' => 'application/json'],
            'timeout'  => 10
        ]);
        try {
            $response = $http->post('/oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => env('OAUTH_CLIENT_ID'),
                    'client_secret' => env('OAUTH_CLIENT_SECRET'),
                    'username' => $request->username,
                    'password' => $request->password
                ]
            ]);
            return $response->getBody();
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            if ($e->getCode() === 400) {
                return response()->json('Invalid Request. Please enter a username or a password.', $e->getCode());
            } else if ($e->getCode() === 401) {
                return response()->json('Your credentials are incorrect. Please try again', $e->getCode());
            }
            return response()->json('Something went wrong on the server.', $e->getCode());
        }
    }

    public function validation(Request $request)
    {
        return Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string'
        ]);
    }
}
