<?php

namespace App\Http\Controllers\Api\Room;

use App\Http\Controllers\Controller;
use App\Services\RoomServices;
use Illuminate\Http\Request;

class AddPayMethodController extends Controller
{
    protected $roomServices;

    public function __construct(RoomServices $roomServices)
    {
        $this->roomServices = $roomServices;
    }

    public function main(Request $request)
    {
        $params = $this->validation($request);
        $this->roomServices->addPayMethods($request->id, $params['pay_methods']);
        return response()->json([], 201);
    }

    public function validation(Request $request)
    {
        return $request->validate([
            'pay_methods' => 'required|array',
            'pay_methods.*' => 'integer|exists:pay_methods,id'
        ]);
    }
}
