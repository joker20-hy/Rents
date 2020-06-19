<?php

namespace App\Http\Controllers\Api\Service;

use App\Http\Controllers\Controller;
use App\Services\ServiceServices;
use Illuminate\Http\Request;

class DestroyController extends Controller
{
    public function __construct(ServiceServices $serviceServices)
    {
        $this->serviceServices = $serviceServices;
    }

    public function main(Request $request)
    {
        $this->serviceServices->destroy($request->id);
        return response()->json([], 200);
    }
}
