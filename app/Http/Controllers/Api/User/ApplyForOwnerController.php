<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\UserServices;
use App\Services\OwnerServices;
use Illuminate\Http\Request;

class ApplyForOwnerController extends Controller
{
    protected $userServices;
    protected $ownerServices;

    public function __construct(UserServices $userServices, OwnerServices $ownerServices)
    {
    	$this->userServices = $userServices;
        $this->ownerServices = $ownerServices;
    }

    public function main(Request $request)
    {
    	$this->validation($request);
        $this->userServices->verifyToken($request->id, $request->token);
        $profile = $request->only(['firstname', 'lastname', 'phone', 'address']);
    	$application = $this->ownerServices->createApplication($request->id, $profile, $request->image);
    	return response()->json($application, 201);
    }

    public function validation(Request $request)
    {
    	return $request->validate([
    		'firstname' => 'required|string',
    		'lastname' => 'required|string',
    		'phone' => 'required|string|min:10|max:10',
    		'address' => 'required|string',
    		'image' => 'required|string',
    	]);
    }
}
