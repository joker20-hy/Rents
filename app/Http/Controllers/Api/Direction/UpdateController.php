<?php

namespace App\Http\Controllers\Api\Direction;

use App\Http\Controllers\Controller;
use App\Services\DirectionServices;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    protected $directionServices;

    public function __construct(DirectionServices $directionServices)
    {
        $this->directionServices = $directionServices;
    }

    public function main(Request $request)
    {
        $params = $this->validation($request);
        $direction = $this->directionServices->update($request->id, $params);
        return response()->json($direction, 201);
    }

    public function validation(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|min:3'
        ]);
    }
}
