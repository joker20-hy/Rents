<?php

namespace App\Http\Controllers\Api\Room;

use App\Http\Controllers\Controller;
use App\Services\RoomServices;
use Illuminate\Http\Request;

class GetServicesController extends Controller
{
    public function __construct(RoomServices $roomServices)
    {
        $this->roomServices = $roomServices;
    }

    public function main(Request $request)
    {
        $services = $this->roomServices->getServices($request->id);
        return response()->json($services, 200);
    }
}
