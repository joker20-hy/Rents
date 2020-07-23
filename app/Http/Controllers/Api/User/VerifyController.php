<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserServices;

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
        $this->userServices->updateVerifyStatus($request->id, $params['verify']);
        return response()->json([], 204);
    }

    public function validation(Request $request)
    {
        return $request->validate([
            'verify' => 'required|integer|min:0',
        ]);
    }
}
