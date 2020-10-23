<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserServices;

class ShowControler extends Controller
{
    protected $userServices;

    public function __construct(UserServices $userServices)
    {
        $this->userServices = $userServices;
    }

    public function main(Request $request)
    {
        $user = $this->userServices->show($request->id);
        return response()->json($user, 200);
    }
}
