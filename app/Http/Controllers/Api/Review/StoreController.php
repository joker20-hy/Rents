<?php

namespace App\Http\Controllers\Api\Review;

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
        $review = $this->reviewServices->store($params, $request->type);
        return response()->json($review, 201);
    }

    private function validation(Request $request)
    {
        return $request->validate([
            'criteria_id' => 'nullable|integer',
            'title' => 'nullable|string',
            'owner_rate' => 'nullable|integer|min:1|max:10',
            'secure_rate' => 'nullable|integer|min:1|max:10',
            'renter_rate' => 'nullable|integer|min:1|max:10',
            'infra_rate' => 'nullable|integer|min:1|max:10',
            'anonymous' => 'nullable|boolean',
            'comment' => 'nullable|string'
        ]);
    }
}
