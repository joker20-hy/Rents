<?php

namespace App\Http\Controllers\Api\Criteria;

use App\Http\Controllers\Controller;
use App\Services\CriteriaServices;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    protected $criteriaServices;

    public function __construct(CriteriaServices $criteriaServices)
    {
        $this->criteriaServices = $criteriaServices;
    }

    public function main(Request $request)
    {
        $params = $this->validation($request);
        $criteria = $this->criteriaServices->store($params);
        return response()->json($criteria, 201);
    }

    public function validation(Request $request)
    {
        return $request->validate([
            'name' => 'required|string',
            'icon' => 'required|string',
        ]);
    }
}
