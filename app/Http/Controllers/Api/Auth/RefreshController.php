<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RefreshController extends Controller
{
    public function main(Request $request)
    {
        $http = new \GuzzleHttp\Client([
            'base_uri' => env('OAUTH_URL'),
            'headers'  => ['Accept' => 'application/json'],
            'timeout'  => 10
        ]);
        $response = $http->post('/oauth/token', [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'client_id' => env('OAUTH_CLIENT_ID'),
                'client_secret' => env('OAUTH_CLIENT_SECRET'),
                'refresh_token' => $request->refresh_token,
                'scope' => '',
            ],
        ]);
        return json_decode((string) $response->getBody(), true);
    }
}
