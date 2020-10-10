<?php

namespace App\Http\Controllers\Api\Review;

use App\Http\Controllers\Controller;
use App\Services\ReviewServices;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    protected $reviewServices;

    public function __construct(ReviewServices $reviewServices)
    {
        $this->reviewServices = $reviewServices;
    }

    public function main(Request $request)
    {
        $reviews = $this->reviewServices->room($request->id);
    }
}
