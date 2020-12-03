<?php

namespace App\Http\Controllers\Api\Room\Review;

use App\Http\Controllers\Controller;
use App\Services\ReviewServices;
use Illuminate\Http\Request;

class StoreController extends Controller
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
        $review = $this->reviewServices->store($params, config('const.REVIEW.TYPE.ROOM'));
        return response()->json($review, 201);
    }

    public function validation(Request $request)
    {
        return $request->validate([
            'title' => 'nullable|string',
            'criteria_id' => 'nullable|integer',
            'owner_rate' => 'nullable|integer|min:1|max:10',
            'secure_rate' => 'nullable|integer|min:1|max:10',
            'infra_rate' => 'nullable|integer|min:1|max:10',
            'anonymous' => 'nullable|boolean',
            'description' => 'nullable|string'
        ]);
    }
}
