<?php

namespace App\Http\Controllers\Api\House;

use App\Http\Controllers\Controller;
use App\Services\HouseServices;
use Illuminate\Http\Request;

class UpdateImagesController extends Controller
{
    protected $houseServices;

    public function __construct(HouseServices $houseServices)
    {
        $this->houseServices = $houseServices;        
    }

    public function main(Request $request)
    {
    	$params = $this->validation($request);
    	$this->houseServices->updateImages($request->id, $params['images']);
    	return response()->json([], 204);
    }

    public function validation(Request $request)
    {
    	return $request->validate([
    		'images' => 'required|array'
    	]);
    }
}
