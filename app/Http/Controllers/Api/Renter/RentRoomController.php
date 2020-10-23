<?php

namespace App\Http\Controllers\Api\Renter;

use App\Http\Controllers\Controller;
use App\Services\RenterServices;
use Illuminate\Http\Request;

/**
 * @author joker20.hy
 */
class RentRoomController extends Controller
{
    protected $renterServices;

    public function __construct(RenterServices $renterServices)
    {
        $this->renterServices = $renterServices;
    }

    public function main(Request $request)
    {
        $params = $this->validation($request);
        $renter = $this->renterServices->rentRoom($request->room_id, $request->user_id);
        return response()->json($renter, 201);
    }

    public function validation(Request $request)
    {
        return $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'user_id' => 'nullable|exists:users,id',
        ]);
    }
}
