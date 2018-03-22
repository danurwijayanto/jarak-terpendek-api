<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\PlaceCode;
use App\Model\PlaceDetails;
use App\Model\CodeDetails;

trait Algoritm{
    public function getDistance(Request $request){
        // Define variabel
        $from = $request->dari;
        $destination = $request->tujuan;
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial";
        $origins = "&origins=-7.074161,110.432116";
        $destination = "&destinations=-6.981927,110.366058";
        $key = "&key=".env("GMAPS_TOKEN");

        
        // $from = PlaceDetails::find($from);
        // $destination = PlaceDetails::find($destination);
        // $allDestination = PlaceDetails::get();

        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', $url.$origins.$destination.$key);
        // dd($res->getStatusCode());
        // dd($res->getHeaderLine('content-type'));
        dd(json_decode($res->getBody()));
    }
}