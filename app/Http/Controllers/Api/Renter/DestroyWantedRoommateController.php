<?php

namespace App\Http\Controllers\Api\Renter;

use App\Http\Controllers\Controller;
use App\Services\RenterServices;
use Illuminate\Http\Request;

class DestroyWantedRoommateController extends Controller
{
    protected $renterServices;

    public function __construct(RenterServices $renterServices)
    {
        $this->renterServices = $renterServices;
    }

    public function main(Request $request)
    {
        $this->renterServices->deleteRoommateWanted();
        return response()->json([], 200);
    }
}
