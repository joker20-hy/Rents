<?php

namespace App\Http\Controllers\Api\Room;

use App\Http\Controllers\Controller;
use App\Services\RoomServices;
use Illuminate\Http\Request;

class PayMethodController extends Controller
{
    protected $roomServices;

    public function __construct(RoomServices $roomServices)
    {
        $this->roomServices = $roomServices;
    }

    public function main(Request $request)
    {
        $paymethods = $this->roomServices->findPayMethods($request->id);
        return response()->json($paymethods, 200);
    }
}
