<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\PlaceDetails;
use App\Traits\Algoritm;

class ClientController extends Controller
{
    use Algoritm;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get Data
        $get_place = PlaceDetails::orderBy("pd_name", "asc")->get();

        // Mapping
        $data['lokasi'] = $get_place;

        return view('contentClient.client.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function calculate(Request $request){
        // dd('asdasd');
        $process = ($this->algoritm_index($request));
        dd($process);
        $url = "https://maps.googleapis.com/maps/api/directions/json?";
        $origins = "origin=-7.064537,110.412407";
        $destination = "&destination=-7.092464,110.4092";
        $key = "&key=".env("GMAPS_TOKEN");
        $client = new \GuzzleHttp\Client();
        // dd($url.$origins.$destination.$key);
        // $res = $client->request('GET', $url.$origins.$destination.$key);
        // dd($res->getStatusCode());
        // dd($res->getHeaderLine('content-type'));
        // dd(json_decode($res->getBody(), true));

        return view('contentClient.client.ruteTerpendek');
    }
}
