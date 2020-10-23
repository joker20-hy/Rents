<?php

namespace App\Http\Controllers\Api\District;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DistrictServices;

class UpdateController extends Controller
{
    protected $districtServices;

    public function __construct(DistrictServices $districtServices)
    {
        $this->districtServices = $districtServices;
    }

    public function main(Request $request)
    {
        $params = $this->validation($request);
        $this->districtServices->update($request->id, $params);
        return response()->json([], 204);
    }

    public function validation(Request $request)
    {
        return $request->validate( [
            'name' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255',
        ]);
    }
}
