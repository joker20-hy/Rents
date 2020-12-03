<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Services\OwnerServices;
use Illuminate\Http\Request;

class DeclineApplicationController extends Controller
{
    protected $ownerServices;

    public function __construct(OwnerServices $ownerServices)
    {
    	$this->ownerServices = $ownerServices;
    }

    public function main(Request $request)
    {
    	$params = $this->validation($request);
    	$this->ownerServices->declineApplication($request->id, $params['decline_reasons']);
    	return response()->json([], 204);
    }

    public function validation(Request $request)
    {
    	return $request->validate([
    		'decline_reasons' => 'required|string'
    	]);
    }
}
