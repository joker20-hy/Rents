<?php

namespace App\Http\Controllers\Api\Room;

use App\Http\Controllers\Controller;
use App\Services\RoomServices;
use Illuminate\Http\Request;

class UpdateImagesController extends Controller
{
    protected $roomServices;

    public function __construct(RoomServices $roomServices)
    {
        $this->roomServices = $roomServices;
    }

    public function main(Request $request)
    {
        $params = $this->validation($request);
    	$this->roomServices->updateImages($request->id, $params['images']);
    	return response()->json([], 204);
    }

    public function validation(Request $request)
    {
    	return $request->validate([
    		'images' => 'required|array'
    	]);
    }
}
