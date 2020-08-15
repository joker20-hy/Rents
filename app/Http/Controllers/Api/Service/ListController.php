<?php

namespace App\Http\Controllers\Api\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ServiceServices;

class ListController extends Controller
{
    protected $serviceServices;

    public function __construct(ServiceServices $serviceServices)
    {
        $this->serviceServices = $serviceServices;
    }

    public function main(Request $request)
    {
        $services = $this->serviceServices->list();
        return response()->json($services, 200);
    }
}
