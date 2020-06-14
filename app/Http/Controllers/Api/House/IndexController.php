<?php

namespace App\Http\Controllers\Api\House;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\HouseServices;

class IndexController extends Controller
{
    protected $houseServices;

    public function __construct(HouseServices $houseServices)
    {
        $this->houseServices = $houseServices;
    }

    public function main(Request $request)
    {
        $houses = $this->houseServices->index($request->province, $request->district, $request->area);
        return response()->json($houses, 200);
    }
}
