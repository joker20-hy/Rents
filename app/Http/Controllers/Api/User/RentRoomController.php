<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\UserServices;
use Illuminate\Http\Request;

class RentRoomController extends Controller
{
    protected $userServices;

    public function __construct(UserServices $userServices)
    {
        $this->userServices = $userServices;
    }

    public function main(Request $request)
    {
        $this->userServices->rentRoom($request->room_id);
        return response()->json([], 204);
    }
}
