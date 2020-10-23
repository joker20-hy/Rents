<?php

namespace App\Http\Controllers\Api\Image;

use App\Http\Controllers\Controller;
use App\Services\ImageServices;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    protected $imageServices;

    public function __construct(ImageServices $imageServices)
    {
        $this->imageServices = $imageServices;
    }

    public function main(Request $request)
    {
        $images = $this->validation($request);
        $urls = $this->imageServices->store($images['images']);
        return response()->json([
            'urls' => $urls
        ], 201);
    }

    public function validation(Request $request)
    {
        return $request->validate([
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    }
}
