<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Services\OwnerServices;
use Illuminate\Http\Request;

class ListApplicationController extends Controller
{
    protected $ownerServices;

    public function __construct(OwnerServices $ownerServices)
    {
    	$this->ownerServices = $ownerServices;
    }

    public function main(Request $request)
    {
    	$applications = $this->ownerServices->listApplication($request->id);
    	return response()->json($applications, 200);
    }
}
