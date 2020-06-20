<?php

namespace App\Http\Controllers\Api\Criteria;

use App\Http\Controllers\Controller;
use App\Services\CriteriaServices;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    protected $criteriaServices;

    public function __construct(CriteriaServices $criteriaServices)
    {
        $this->criteriaServices = $criteriaServices;
    }

    public function main(Request $request)
    {
        $params = $this->validation($request);
        $this->criteriaServices->update($request->id, $params);
        return response()->json([], 204);
    }

    public function validation(Request $request)
    {
        return $request->validate([
            'name' => 'required|string'
        ]);
    }
}
