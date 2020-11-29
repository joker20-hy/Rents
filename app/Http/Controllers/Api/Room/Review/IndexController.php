<?php

namespace App\Http\Controllers\Api\Room\Review;

use App\Http\Controllers\Controller;
use App\Services\ReviewServices;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    protected $reviewServices;

    public function __construct(ReviewServices $reviewServices)
    {
        $this->reviewServices = $reviewServices;
    }

    public function main(Request $request)
    {
        $reviews = $this->reviewServices->list(config('const.REVIEW.TYPE.ROOM'), $request->id, $request->perpage);
        return response()->json($reviews, 200);
    }
}
