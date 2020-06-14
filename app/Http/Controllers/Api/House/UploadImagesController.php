<?php

namespace App\Http\Controllers\Api\House;

use App\Http\Controllers\Controller;
use App\Services\HouseServices;
use Illuminate\Http\Request;

class UploadImagesController extends Controller
{
    protected $houseServices;

    public function __construct(HouseServices $houseServices)
    {
        $this->houseServices = $houseServices;        
    }

    public function main(Request $request)
    {
        $files = $this->validation($request);
    	$images = $this->houseServices->uploadImages($request->id, $files['images']);
    	return response()->json($images, 200);
    }

    public function validation(Request $request)
    {
    	return $request->validate([
    		'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    	]);
    }
}
