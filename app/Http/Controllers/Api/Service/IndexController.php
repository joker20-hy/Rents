<?php

namespace App\Http\Controllers\Api\Service;

use App\Http\Controllers\Controller;
use App\Services\ServiceServices;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    protected $serviceServices;

    public function __construct(ServiceServices $serviceServices)
    {
        $this->serviceServices = $serviceServices;
    }

    public function main(Request $request)
    {
        $services = $this->serviceServices->index();
        return response()->json($services, 200);
    }
}
