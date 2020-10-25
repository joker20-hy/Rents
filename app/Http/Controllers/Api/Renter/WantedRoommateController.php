<?php

namespace App\Http\Controllers\Api\Renter;

use App\Http\Controllers\Controller;
use App\Services\RenterServices;
use Illuminate\Http\Request;

class WantedRoommateController extends Controller
{
    protected $renterServices;

    public function __construct(RenterServices $renterServices)
    {
        $this->renterServices = $renterServices;
    }

    public function main(Request $request)
    {
        $params = $this->validation($request);
        $this->renterServices->createRoommateWanted($params);
        return response()->json([], 201);
    }

    public function validation(Request $request)
    {
        return $request->validate([
            'number' => 'required|integer',
            'contact' => 'nullable|string',
            'content' => 'nullable|string',
        ]);
    }
}
