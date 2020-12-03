<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Services\OwnerServices;
use Illuminate\Http\Request;

class ShowApplicationController extends Controller
{
    protected $ownerServices;

    public function __construct(OwnerServices $ownerServices)
    {
    	$this->ownerServices = $ownerServices;
    }

    public function main(Request $request)
    {
    	$application = $this->ownerServices->showApplication($request->id);
    	return response()->json($application, 200);
    }
}
