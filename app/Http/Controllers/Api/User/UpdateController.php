<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\UserServices;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    protected $userServices;

    public function __construct(UserServices $userServices)
    {
        $this->userServices = $userServices;
    }

    public function main(Request $request)
    {
        $params = $this->validation($request);
        $this->userServices->update($request->id, $params);
        return response()->json([], 204);
    }

    private function validation(Request $request)
    {
        return $request->validate([
            'profile' => 'required|array',
            'profile.firstname' => 'required|string',
            'profile.lastname' => 'required|string',
            'profile.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'profile.address' => 'required|string',
            'profile.phone' => 'required|string',
            'profile.date_of_birth' => 'required|date',
            'setting' => 'required|array',
            'setting.verification_2_step' => 'required|boolean',
            'name' => 'required|string'
        ]);
    }
}
