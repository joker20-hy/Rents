<?php

namespace App\Http\Controllers\Api\House;

use App\Http\Controllers\Controller;
use App\Services\HouseServices;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    protected $houseServices;

    public function __construct(HouseServices $houseServices)
    {
        $this->houseServices = $houseServices;
    }

    public function main(Request $request)
    {
        $params = $this->validation($request);
        $house = $this->houseServices->store($params);
        return response()->json($house, 200);
    }

    public function validation(Request $request)
    {
        return $request->validate([
            'name' => 'required|string',
            'province_id' => 'required|integer|min:1',
            'district_id' => 'required|integer|min:1',
            'area_id' => 'required|integer|min:1',
            'address' => 'required|string|min:2',
            'rent' => 'required|boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'direction' => 'nullable|integer',
            'price' => 'nullable|integer',
            'description' => 'nullable|string',
            'services' => 'nullable|array'
        ]);
    }
}
