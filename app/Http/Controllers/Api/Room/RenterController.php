<?php

namespace App\Http\Controllers\Api\Room;

use App\Http\Controllers\Controller;
use App\Services\RoomServices;
use Illuminate\Http\Request;

class RenterController extends Controller
{
    protected $roomServices;

    public function __construct(RoomServices $roomServices)
    {
        $this->roomServices = $roomServices;
    }

    public function main(Request $request)
    {
        $renters = $this->roomServices->renters($request->id, $request->renter);
        return response()->json($renters, 200);
    }
}
