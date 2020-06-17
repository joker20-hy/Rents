<?php

namespace App\Http\Controllers\Api\Room;

use App\Http\Controllers\Controller;
use App\Services\RoomServices;
use Illuminate\Http\Request;

class UploadImagesController extends Controller
{
    protected $roomServices;

    public function __construct(RoomServices $roomServices)
    {
        $this->roomServices = $roomServices;
    }

    public function main(Request $request)
    {
        $files = $this->validation($request);
    	$images = $this->roomServices->uploadImages($request->id, $files['images']);
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
