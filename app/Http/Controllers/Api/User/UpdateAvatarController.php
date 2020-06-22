<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\ProfileServices;
use Illuminate\Http\Request;

class UpdateAvatarController extends Controller
{
    public function __construct(ProfileServices $profileServices)
    {
        $this->profileServices = $profileServices;
    }

    public function main(Request $request)
    {
        $image = $this->validation($request);
        $url = $this->profileServices->updateAvatar($request->id, $image);
        return response()->json($url, 200);
    }

    public function validation(Request $request)
    {
        return $request->validate([
            'image.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    }
}
