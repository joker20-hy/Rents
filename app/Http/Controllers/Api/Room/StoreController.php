<?php

namespace App\Http\Controllers\Api\Room;

use App\Http\Controllers\Controller;
use App\Services\RoomServices;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    protected $roomServices;

    public function __construct(RoomServices $roomServices)
    {
        $this->roomServices = $roomServices;
    }

    public function main(Request $request)
    {
        $params = $this->validation($request);
        $room = $this->roomServices->store($params);
        return response()->json($room, 200);
    }

    public function validation(Request $request)
    {
        return $request->validate([
            'house_id' => 'required|exists:houses,id',
            'name' => 'required|string',
            'acreage' => 'required|numeric|min:0',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required|string'
        ]);
    }
}
