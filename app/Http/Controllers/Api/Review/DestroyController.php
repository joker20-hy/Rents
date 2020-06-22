<?php

namespace App\Http\Controllers\Api\Review;

use App\Http\Controllers\Controller;
use App\Services\ReviewServices;
use Illuminate\Http\Request;

class DestroyController extends Controller
{
    protected $reviewServices;

    public function __construct(ReviewServices $reviewServices)
    {
        $this->reviewServices = $reviewServices;
    }

    public function main(Request $request)
    {
    	$this->reviewServices->destroy($request->id);
    	return response()->json([], 200);
    }
}
