<?php

namespace App\Http\Controllers\Api\Renter;

use App\Http\Controllers\Controller;
use App\Services\ReviewServices;
use Illuminate\Http\Request;

/**
 * @author joker20.hy
 */
class ReviewController extends Controller
{
    protected $reviewServices;

    public function __construct(ReviewServices $reviewServices)
    {
        $this->reviewServices = $reviewServices;
    }

    public function main(Request $request)
    {
    	$params = $this->validation($request);
    	$params['criteria_id'] = $request->id;
        $review = $this->reviewServices->store($params, $request->type);
        return response()->json($review, 201);
    }

    public function validation(Request $request)
    {
    	return $request->validate([
    		'title' => 'nullable|string',
            'rate' => 'required|integer|min:1|max:10',
            'anonymous' => 'nullable|boolean',
            'description' => 'nullable|string'
    	]);
    }
}
