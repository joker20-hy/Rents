<?php

namespace App\Http\Controllers\Api\Renter;

use App\Http\Controllers\Controller;
use App\Services\RenterServices;
use Illuminate\Http\Request;

/**
 * @author joker20.hy
 */
class LeaveRoomController extends Controller
{
    protected $renterServices;

    public function __construct(RenterServices $renterServices)
    {
        $this->renterServices = $renterServices;
    }

    public function main(Request $request)
    {
        $this->renterServices->leaveRoom($request->userId);
        return response()->json([], 204);
    }
}
