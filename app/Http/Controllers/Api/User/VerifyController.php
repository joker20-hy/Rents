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
        $validator = $this->validation($request);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 422);
        }
        $this->userServices->updateVerifyStatus($request->userId, $request->verify);
        return response()->json([], 204);
    }

    public function validation(Request $request)
    {
        return Validator::make($request->all(), [
            'verify' => 'required|integer|min:0',
        ]);
    }
}
