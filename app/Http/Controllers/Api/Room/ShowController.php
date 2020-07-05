<?php

namespace App\Http\Controllers\Api\room;

use App\Http\Controllers\Controller;
use App\Services\RoomServices;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    protected $roomServices;

    public function __construct(RoomServices $roomServices)
    {
        $this->roomServices = $roomServices;
    }

    public function main(Request $request)
    {
        $room = $this->roomServices->show($request->id);
        return response()->json($room, 200);
    }
}
