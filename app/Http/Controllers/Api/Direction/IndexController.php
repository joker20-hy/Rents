<?php

namespace App\Http\Controllers\Api\Direction;

use App\Http\Controllers\Controller;
use App\Services\DirectionServices;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    protected $directionServices;

    public function __construct(DirectionServices $directionServices)
    {
        $this->directionServices = $directionServices;
    }

    public function main(Request $request)
    {
        $directions = $this->directionServices->index();
        return response()->json($directions, 200);
    }
}
