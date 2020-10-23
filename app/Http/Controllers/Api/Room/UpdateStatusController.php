<?php

namespace App\Http\Controllers\Api\Room;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\RoomServices;

class UpdateStatusController extends Controller
{
    protected $roomServices;

    public function __construct(RoomServices $roomServices)
    {
        $this->roomServices = $roomServices;
    }

    public function main(Request $request)
    {
        $params = $this->validation($request);
        $this->roomServices->updateStatus($request->id, $params);
        return response()->json([], 204);
    }

    private function validation(Request $request)
    {
        return $request->validate([
            'status' => 'required|numeric'
        ]);
    }
}
