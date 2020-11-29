<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProvinceServices;

class HomeController extends Controller
{
    protected $provinceServices;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ProvinceServices $provinceServices)
    {
        $this->provinceServices = $provinceServices;
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $provinces = $this->provinceServices->all();
        return view('app')->with('provinces', $provinces);
    }
}
