<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Services\UserServices;
use Illuminate\Http\Request;

class UpdatePasswordController extends Controller
{
    protected $userServices;

    public function __construct(UserServices $userServices)
    {
        $this->userServices = $userServices;
    }

    public function main(Request $request)
    {
        $params = $this->validation($request);
        $this->userServices->updatePassword($request->id, $params);
        return response()->json([], 204);
    }

    private function validation(Request $request)
    {
        return $request->validate([
            'password' => 'required|string|min:8|confirmed',
            'token' => 'nullable|string|min:7'
        ]);
    }
}
