<?php

namespace App\Http\Controllers\Api\house;

use App\Http\Controllers\Controller;
use App\Services\HouseServices;
use Illuminate\Http\Request;

class ListController extends Controller
{
    protected $houseServices;

    public function __construct(HouseServices $houseServices)
    {
        $this->houseServices = $houseServices;
    }

    public function main(Request $request)
    {
        $houses = $this->houseServices->list($request->province, $request->district, $request->area);
        return response()->json($houses, 200);
    }
}
