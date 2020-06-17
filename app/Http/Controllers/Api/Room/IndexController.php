<?php

namespace App\Http\Controllers\Api\Room;

use App\Http\Controllers\Controller;
use App\Services\RoomServices;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    protected $roomServices;

    public function __construct(RoomServices $roomServices)
    {
        $this->roomServices = $roomServices;
    }

    public function main(Request $request)
    {
        $rooms = $this->roomServices->index([
            'province' => $request->province,
            'district' => $request->district,
            'area' => $request->area,
            'address' => $request->address,
            'house' => $request->house
        ]);
        return response()->json($rooms, 200);
    }
}
