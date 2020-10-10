<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Services\UserServices;
use Illuminate\Http\Request;

class VerifyController extends Controller
{
    protected $userServices;
    
    public function __construct(UserServices $userServices)
    {
        $this->userServices = $userServices;
    }

    public function main(Request $request)
    {
        $params = $this->validation($request);
        $token = $this->userServices->verifyCode($request->id, $params['code']);
        return response()->json($token, 200);
    }

    private function validation(Request $request)
    {
        return $request->validate([
            'code' => 'required|string'
        ]);
    }
}
