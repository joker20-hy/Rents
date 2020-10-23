<?php

namespace App\Http\Controllers\Api\Room;

use App\Http\Controllers\Controller;
use App\Services\RoomServices;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    protected $roomServices;

    public function __construct(RoomServices $roomServices)
    {
        $this->roomServices = $roomServices;
    }

    public function main(Request $request)
    {
        $params = $this->validation($request);
        $this->roomServices->update($request->id, $params);
        return response()->json([], 204);
    }

    public function validation(Request $request)
    {
        return $request->validate([
            'name' => 'required|string',
            'acreage' => 'required|numeric|min:0',
            'description' => 'required|string',
            'price' => 'required|numeric|min:1000',
            'cycle' => 'required|numeric|min:1',
            'criterias' => 'required|array'
        ]);
    }
}
