<?php

namespace App\Http\Controllers\Api\House;

use App\Http\Controllers\Controller;
use App\Services\HouseServices;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    protected $houseServices;

    public function __construct(HouseServices $houseServices)
    {
        $this->houseServices = $houseServices;
    }

    public function main(Request $request)
    {
        $services = $this->houseServices->services($request->id);
        return response()->json($services, 200);
    }
}
