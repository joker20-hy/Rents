<?php

namespace App\Http\Controllers\Api\Criteria;

use App\Http\Controllers\Controller;
use App\Services\CriteriaServices;
use Illuminate\Http\Request;

class DestroyController extends Controller
{
    protected $criteriaServices;

    public function __construct(CriteriaServices $criteriaServices)
    {
        $this->criteriaServices = $criteriaServices;
    }

    public function main(Request $request)
    {
        $this->criteriaServices->destroy($request->id);
        return response()->json([], 200);
    }
}
