<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\PlaceCode;
use App\Model\PlaceDetails;
use App\Model\CodeDetails;
use RFHaversini\Distance;
use App\Plugins\FloydWarshall;

trait Algoritm{
    public function getDistance(Request $request){
        // Define variabel
        $from = $request->dari;
        $destination = $request->tujuan;
        $from = PlaceDetails::find($from);
        $destination = PlaceDetails::find($destination);
        $allDestination = PlaceDetails::get();
        /** GMAPS */
        /*
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial";
        $origins = "&origins=-7.074161,110.432116";
        $destination = "&destinations=-6.981927,110.366058";
        $key = "&key=".env("GMAPS_TOKEN");
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', $url.$origins.$destination.$key);
        dd($res->getStatusCode());
        dd($res->getHeaderLine('content-type'));
        */
        
        if (!empty($allDestination)){
            $index = count($allDestination);
            $node = array();
            $node_id = array();
            $distance_matrix[] = array();
            $calculate_distance = 0;
            
            foreach($allDestination as $destination){
                array_push($node, $destination->pd_name);
                array_push($node_id, $destination->pd_id);
            }

            for ($i=0; $i<$index; $i++){
                for ($j=0; $j<$index; $j++){
                    if ($i == $j){
                        $calculate_distance = 0;
                    }else{
                        $calculate_distance = Distance::toKilometers($allDestination[$i]->pd_latitude, $allDestination[$i]->pd_longitude, $allDestination[$j]->pd_latitude, $allDestination[$j]->pd_longitude);
                    }
                    $distance_matrix[$i][$j] = $calculate_distance;
                }
            }
            // $inKilometers = Distance::toKilometers(-7.074161, 110.432116, -6.981927, 110.366058);
            // dd($node_id);
        }

        // $inMiles      = Distance::toMiles(30.261699, -97.738967, 29.869229, -97.959595);
        //dd(json_decode($inKilometers));

        /** Floyd Warshall */
        $graph = array(array(0,5,8,6),
                      array(5,0,3,1),
                      array(8,3,0,1),
                      array(6,1,1,0));
                    //   array(0,0,10,0,0,4),
                    //   array(0,0,17,20,0,0));
        $nodes = array("a", "b", "c", "d");
        // dd($nodes);
        $fw = new FloydWarshall($graph, $nodes);
        // dd($fw);
        // $fw->print_path(0,3);
        $fw->print_graph();
        // $fw->print_dist();
        // $fw->print_pred();
    
        $sp = $fw->get_path(0,3);
        // dd($sp);
        $jlss = 3;
        echo 'Ruter Terdekat Dari Star Ke '.$nodes[$jlss].' Adalah: <strong>';
        $jl = count($sp);
        $r=1;
        foreach ($sp as $value) {
            echo $nodes[$value];
            if ($r == $jl) {
                
            }
            else{
                echo ' => ';
            }
            $r++;
                
        }
        echo ' | Dengan Jarak Tempuh ';
        $menit = round(($fw->get_distance(0,$jlss)/40)*60,0);
        print_r($fw->get_distance(0,$jlss));
        echo " km yaitu $menit</strong>";
        }
}