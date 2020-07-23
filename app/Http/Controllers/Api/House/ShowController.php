<?php

namespace App\Http\Controllers\Api\House;

use App\Http\Controllers\Controller;
use App\Services\HouseServices;
use Illuminate\Http\Request;

class ShowController extends Controller
{
	protected $houseServices;

    public function __construct(HouseServices $houseServices)
    {
    	$this->houseServices = $houseServices;
    }

    public function main(Request $request)
    {
    	$house = $this->houseServices->show($request->id);
    	return response()->json($house, 200);
    }
}
