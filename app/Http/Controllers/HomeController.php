<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\PlaceCode;
use App\Model\PlaceDetails;

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
        return view('home');
    }

    public function test(){
        $get_data = PlaceCode::where('pc_id', 1)->with('details')->get();

        dd($get_data->toArray());
    }
}
