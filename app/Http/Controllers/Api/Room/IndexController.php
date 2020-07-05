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
        $params = $this->validation($request);
        $rooms = $this->roomServices->index($params);
        return response()->json($rooms, 200);
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
