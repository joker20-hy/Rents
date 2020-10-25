<?php

namespace App\Http\Controllers\Api\House;

use App\Http\Controllers\Controller;
use App\Services\HouseServices;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    protected $houseServices;

    public function __construct(HouseServices $houseServices)
    {
        $this->houseServices = $houseServices;        
    }

    public function main(Request $request)
    {
        $params = $this->validation($request);
        $house = $this->houseServices->update($request->id, $params);
        return response()->json($house, 200);
    }

    public function validation(Request $request)
    {
    	return $request->validate([
    		'name' => 'required|string',
    		'province_id' => 'required|integer|exists:provinces,id',
    		'district_id' => 'required|integer|exists:districts,id',
    		'area_id' => 'nullable|integer|exists:areas,id',
    		'rent' => 'required|boolean',
            'price' => 'nullable|numeric',
            'direction' => 'nullable|integer',
    		'description' => 'nullable|string',
    		'addition_images' => 'nullable|array',
            'addition_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'add_services' => 'nullable|array',
            'remove_services' => 'nullable|array',
            'contact' => 'nullable|array',
            'contact.phone' => 'nullable|numeric',
            'contact.others' => 'nullable|string'
    	]);
    }
}
