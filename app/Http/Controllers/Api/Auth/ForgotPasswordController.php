<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Services\UserServices;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    protected $userServices;

    public function __construct(UserServices $userServices)
    {
        $this->userServices = $userServices;
    }

    public function main(Request $request)
    {
        $params = $this->validation($request);
        $user = $this->userServices->forgotPassword($params['email']);
        return response()->json([
            'id' => $user->id
        ], 200);
    }

    private function validation(Request $request)
    {
        return $request->validate([
            'email' => 'required|email'
        ]);
    }
}
