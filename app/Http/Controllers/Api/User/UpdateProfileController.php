<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserServices;

class UpdateProfileController extends Controller
{
    protected $userServices;

    public function __construct(UserServices $userServices)
    {
        $this->userServices = $userServices;
    }

    public function main(Request $request)
    {
        $params = $this->getParams($request);
        $validator = $this->validation($params);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 422);
        }
        $this->userServices->updateProfile($request->userId, $params);
        return response()->json([], 204);
    }

    public function getParams(Request $request)
    {
        return $request->only('firstname', 'lastname', 'phone', 'address', 'date_of_birth');
    }

    public function validation(array $params)
    {
        return Validator::make($params, [
            'firstname' => 'required|string|min:0',
            'lastname' => 'required|string|min:0',
            'phone' => 'required|string|min:0',
            'address' => 'required|string|min:0',
            'date_of_birth' => 'required|date|min:0'
        ]);
    }
}
