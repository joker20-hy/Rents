<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Services\OwnerServices;
use Illuminate\Http\Request;

class UpdateApplicationController extends Controller
{
    protected $ownerServices;

    public function __construct(OwnerServices $ownerServices)
    {
    	$this->ownerServices = $ownerServices;
    }

    public function main(Request $request)
    {
    	$this->validation($request);
    	$profile = $request->only(['firstname', 'lastname', 'phone', 'address']);
    	$this->ownerServices->updateApplication($request->id, $profile, $request->image);
    	return response()->json([], 204);
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
