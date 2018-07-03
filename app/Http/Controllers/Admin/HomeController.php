<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Model\PlaceDetails;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get Data
        $get_place = PlaceDetails::orderBy("pd_name", "asc")->get();

        // Mapping
        $data['lokasi'] = $get_place;

        return view('content.homepage.index', $data);
    }
}
