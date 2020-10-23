<?php

namespace App\Http\Controllers\Api\Suggest;

use App\Http\Controllers\Controller;
use App\Services\SuggestServices;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    protected $suggestServices;

    public function __construct(SuggestServices $suggestServices)
    {
        $this->suggestServices = $suggestServices;
    }

    public function main(Request $request)
    {
        $addresses = $this->suggestServices->address($request->keywords);
        return response()->json($addresses, 200);
    }
}
