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
        $params = $this->validation($request);
        $this->userServices->updateProfile($request->id, $params);
        return response()->json([], 204);
    }

    public function validation(Request $request)
    {
        return $request->validate([
            'firstname' => 'required|string|min:0',
            'lastname' => 'required|string|min:0',
            'phone' => 'required|string|min:0',
            'address' => 'required|string|min:0',
            'date_of_birth' => 'required|date|min:0'
        ]);
    }
}
