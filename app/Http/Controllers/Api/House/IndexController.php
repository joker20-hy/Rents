<?php

namespace App\Http\Controllers\Api\House;

use App\Http\Controllers\Controller;
use App\Services\HouseServices;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    protected $houseServices;

    public function __construct(HouseServices $houseServices)
    {
        $this->houseServices = $houseServices;
    }

    public function main(Request $request)
    {
        $params = $this->validation($request);
        $houses = $this->houseServices->index($params);
        return response()->json($houses, 200);
    }

    public function validation(Request $request)
    {
        return $request->validate([
            'province' => 'nullable||string',
            'district' => 'nullable||string',
            'area' => 'nullable||string',
            'lat' => 'nullable||double',
            'lng' => 'nullable||double',
            'address' => 'nullable||string'
        ]);
    }
}
