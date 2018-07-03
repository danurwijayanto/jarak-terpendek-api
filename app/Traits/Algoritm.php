<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\PlaceCode;
use App\Model\PlaceDetails;
use App\Model\CodeDetails;
use RFHaversini\Distance;
use App\Plugins\FloydWarshall;
use App\Plugins\FloydWarshallOriginal;

trait Algoritm{
    public function algoritm_index(Request $request){
        //dd(Distance::toKilometers(-7.017465 ,110.494001, -7.003089, 110.450061));
        
        $from = PlaceDetails::find($request->dari);
        $destination = PlaceDetails::find($request->tujuan);

        // echo "<table border='1' width='100%'><tbody><tr><td>";

        $nama_jalan = array();
        $transit = array();
        $transit['angkot'] = array();
        $lat = array();
        $lon = array();
       
        // $data = CodeDetails::whereIn('pc_id', function($query) use ($request){
        $data = CodeDetails::get();
            // $query->select('pc_id')
            // ->from('code_detail')
            // ->where('pd_id', '=', $request->tujuan)->orWhere('pd_id_destination', '=', $request->dari)
            // ->whereRaw('(pd_id = '.$request->tujuan.' and pd_id_destination = '.$request->dari.') or (pd_id = '.$request->dari.' and pd_id_destination = '.$request->tujuan.')')
            // ->groupBy('pc_id');
        // })->with('details')->with('details_code')->get();
        // dd($data);
        foreach($data as $d) {
            if (in_array($d->details->pd_name,$nama_jalan)){
                // $transit['place_name'][] = $d->details->pd_name;
            }else{
                $nama_jalan[] = $d->details->pd_name;
                $lat[] = $d->details->pd_latitude;
                $long[] = $d->details->pd_longitude;
                
                // echo "</td><td>".$d->details->pd_name;
                // if (!in_array($d->details_code->pc_name,$transit['angkot'])){
                //     $transit['angkot'][] = $d->details_code->pc_name;
                // }
            }
        }
        // dd($nama_jalan);

        $jlhsas = count($nama_jalan)-1;
        //dd($nama_jalan);
        $n = 0;
        foreach ($nama_jalan as $key1 => $value1) {
            // echo "</td></tr><tr><td>$value1";
            foreach ($nama_jalan as $key => $value) {

                $nama[$key1][$key] = $value1.'-'.$value;
            
                
                if ($value == $value1) {
                    $dataf = 0;
                }
                
                if ($key1>$key) {
                    // if ($key == 0 and $key1 == $jlhsas) {
                    //     $dataf = "∞";
                    // }
                    // if (($value[$key-1] != $value1) or ($value[$key+1] != $value1)){
                    //     $dataf = "∞";
                    // }
                    $place_from = PlaceDetails::where('pd_name', 'like', '%'.$value1.'%')->first();
                    $place_dest = PlaceDetails::where('pd_name', 'like', '%'.$value.'%')->first();
                    
                    $relation = CodeDetails::whereRaw('(pd_id = '.$place_from->pd_id.' and pd_id_destination = '.$place_dest->pd_id.') or (pd_id = '.$place_dest->pd_id.' and pd_id_destination = '.$place_from->pd_id.')')->first();
                    
                    if (count($relation) > 0){
                        $dataf = $relation->distance;
                    }else{
                        $dataf = "∞";
                    }

                    // if (($key != 0) and (($nama_jalan[$key-1] != $value1) and ($nama_jalan[$key+1] != $value1))){
                    //     $dataf = "∞";
                    // }else if ($nama_jalan[$key+1] != $value1){
                    //     $dataf = "∞";
                    // }else{
                    //     $asala   = $lat[$key].",".$long[$key];
                    //     $tujuana =  $lat[$key1].",".$long[$key1];
                    //     $jarak = Distance::toKilometers($lat[$key] ,$long[$key], $lat[$key1], $long[$key1]);
                        
                    //     $dataf = $jarak;
                    //     //include "distans.php";
                    // }
            
                    // echo "</td><td align='right'>".$dataf;//.' >'.$key.','.$key1;//.'='.$nama[$key][$key1];
                }
                elseif ($key1 == $key) {
                    $dataf=0;
                    // echo "</td><td align='right'>".$dataf;
                }
                else{
                    // if ($key1 == 0 and $key == $jlhsas) {
                    //     $dataf = "∞";
                    // }

                    $place_from = PlaceDetails::where('pd_name', 'like', '%'.$value1.'%')->first();
                    $place_dest = PlaceDetails::where('pd_name', 'like', '%'.$value.'%')->first();
                    
                    $relation = CodeDetails::whereRaw('(pd_id = '.$place_from->pd_id.' and pd_id_destination = '.$place_dest->pd_id.') or (pd_id = '.$place_dest->pd_id.' and pd_id_destination = '.$place_from->pd_id.')')->first();
                    
                    if (count($relation) > 0){
                        $dataf = $relation->distance;
                    }else{
                        $dataf = "∞";
                    }

                    // if (($key1 != 0) and (($nama_jalan[$key1-1] != $value) and ($nama_jalan[$key1+1] != $value))){
                    //     $dataf = "∞";
                    // }else if ($nama_jalan[$key1+1] != $value){
                    //     $dataf = "∞";
                    // }else{
                    //     $asala   = $lat[$key1].",".$long[$key1];
                    //     $tujuana =  $lat[$key].",".$long[$key];
                    //     $jarak = Distance::toKilometers($lat[$key] ,$long[$key], $lat[$key1], $long[$key1]);
                    //     $dataf = $jarak;
                    //     //include "distans.php";
                    // }
            
                    // echo "</td><td align='right'>".$dataf;//. ' > '.$key1.','.$key;//.'='.$nama[$key1][$key];
                }
                    
                $graph[$key1][] = str_replace(" km", "", str_replace(",", ".", $dataf));
            }
            $nodes[] = $value1;
            $n++;
        }
        // echo "</td></tr></tbody></table>";
        // $jlss = count($nama_jalan)-1;
        $index_from =  array_search($from->pd_name, $nama_jalan);
        $index_destination =  array_search($destination->pd_name, $nama_jalan);
        // echo "<br><br>";
        $return_data['get_Distance'] = $this->getDistanceOriginal($graph, $nodes, $index_from, $index_destination);
        
        // Mengholah Jalur dan Angkot yang bisa dilewati
        $jumlah_jalan = count($return_data['get_Distance']);
        $transit['place_name'] = $return_data['get_Distance'];
        
        for ($i=0; $i<$jumlah_jalan-1; $i++){
            $place_from = PlaceDetails::where('pd_name', 'like', '%'.$return_data['get_Distance'][$i].'%')->first();
            $place_dest = PlaceDetails::where('pd_name', 'like', '%'.$return_data['get_Distance'][$i+1].'%')->first();
            
            $relation = CodeDetails::whereRaw('(pd_id = '.$place_from->pd_id.' and pd_id_destination = '.$place_dest->pd_id.') or (pd_id = '.$place_dest->pd_id.' and pd_id_destination = '.$place_from->pd_id.')')->with('details_code')->get();
            // dd($relation);
            if (count($relation) > 0){
                foreach($relation as $list){
                    if (!in_array($list->details_code->pc_name,$transit['angkot'])){
                        $transit['angkot'][] = $list->details_code->pc_name;
                    }
                }
            }
        }

        $return_data['transit'] = $transit;
        
        return $return_data;
    }
    
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
        $fw = new FloydWarshall($distance_matrix, $node);
        // dd($fw);
        // $fw->print_path(0,3);
        //$fw->print_graph();
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

    public function getDistanceOriginal($graph, $nodes, $index_from, $index_destination){
        
        //$graph = array(array(0,5,8,6),
        //               array(5,0,3,1),
        //               array(8,3,0,1),
        //               array(6,1,1,0));
        //               array(0,0,10,0,0,4),
        //               array(0,0,17,20,0,0));
        //$nodes = array("a", "b", "c", "d");
    
        $fw = new FloydWarshall($graph, $nodes);
        //$fw->print_path(0,2);
        //$fw->print_graph();
        // $fw->print_dist();
        //$fw->print_pred();
    
        $sp = $fw->get_path($index_from,$index_destination);
    
        // echo 'Ruter Terdekat Dari Start '.$nodes[$index_from].' Ke '.$nodes[$index_destination].' Adalah: <strong>';
        $jl = count($sp);
        $r=1;

        $final_node = array();

        foreach ($sp as $value) {
            // echo $nodes[$value];
            array_push($final_node, $nodes[$value]);
            if ($r == $jl) {
                
            }
            else{
                // echo ' => ';
            }
            $r++;
                
        }
        return $final_node;
        echo ' | Dengan Jarak Tempuh ';
        $menit = round(($fw->get_distance($index_from,$index_destination)/40)*60,0);
        print_r($fw->get_distance($index_from,$index_destination));
        echo " km yaitu $menit</strong>";
    }
}