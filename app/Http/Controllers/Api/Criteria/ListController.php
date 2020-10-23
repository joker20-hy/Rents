<?php

namespace App\Http\Controllers\Api\criteria;

use App\Http\Controllers\Controller;
use App\Services\CriteriaServices;
use Illuminate\Http\Request;

class ListController extends Controller
{
    protected $criteriaServices;

    public function __construct(CriteriaServices $criteriaServices)
    {
        $this->criteriaServices = $criteriaServices;        
    }

    public function main(Request $request)
    {
        $criterias = $this->criteriaServices->list();
        return response()->json($criterias, 200);
    }
}
