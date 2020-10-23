<?php

namespace App\Http\Controllers\Api\Direction;

use App\Http\Controllers\Controller;
use App\Services\DirectionServices;
use Illuminate\Http\Request;

class DestroyController extends Controller
{
    protected $directionServices;

    public function __construct(DirectionServices $directionServices)
    {
        $this->directionServices = $directionServices;
    }

    public function main(Request $request)
    {
        $this->directionServices->destroy($request->id);
        return response()->json([], 204);
    }
}
