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
        
        $from = PlaceDetails::find($request->dari);
        $destination = PlaceDetails::find($request->tujuan);
        
        // Print table
        // echo "<table border='1' width='100%'><tbody><tr><td>";

        $nama_jalan = array();
        $transit = array();
        $transit['angkot'] = array();
        $lat = array();
        $lon = array();
       
        // $data = CodeDetails::whereIn('pc_id', function($query) use ($request){
        // $query->select('pc_id')
        // ->from('code_detail')
        // ->where('pd_id', '=', $request->tujuan)->orWhere('pd_id_destination', '=', $request->dari)
        // ->whereRaw('(pd_id = '.$request->tujuan.' and pd_id_destination = '.$request->dari.') or (pd_id = '.$request->dari.' and pd_id_destination = '.$request->tujuan.')')
        // ->groupBy('pc_id');
        // })->with('details')->with('details_code')->get();
        // dd($data);

        $data = CodeDetails::get();
        foreach($data as $d) {
            if (in_array($d->details->pd_name,$nama_jalan)){

            }else{
                $nama_jalan[] = $d->details->pd_name;
                $lat[] = $d->details->pd_latitude;
                $long[] = $d->details->pd_longitude;
                
                // Print Table
                // echo "</td><td>".$d->details->pd_name;
            }
        }

        $jlhsas = count($nama_jalan)-1;

        $n = 0;
        foreach ($nama_jalan as $key1 => $value1) {
            
            // Print Table
            // echo "</td></tr><tr><td>$value1";
            
            foreach ($nama_jalan as $key => $value) {

                $nama[$key1][$key] = $value1.'-'.$value;
            
                
                if ($value == $value1) {
                    $dataf = 0;
                }
                
                if ($key1>$key) {

                    $place_from = PlaceDetails::where('pd_name', 'like', '%'.$value1.'%')->first();
                    $place_dest = PlaceDetails::where('pd_name', 'like', '%'.$value.'%')->first();
                    
                    $relation = CodeDetails::whereRaw('(pd_id = '.$place_from->pd_id.' and pd_id_destination = '.$place_dest->pd_id.') or (pd_id = '.$place_dest->pd_id.' and pd_id_destination = '.$place_from->pd_id.')')->first();
                    
                    if (count($relation) > 0){
                        $dataf = $relation->distance;
                    }else{
                        $dataf = "∞";
                    }
                    
                    // Print Table
                    // echo "</td><td align='right'>".$dataf;//.' >'.$key.','.$key1;//.'='.$nama[$key][$key1];
                }
                elseif ($key1 == $key) {
                    $dataf=0;
                    // Print Table
                    // echo "</td><td align='right'>".$dataf;
                }
                else{

                    $place_from = PlaceDetails::where('pd_name', 'like', '%'.$value1.'%')->first();
                    $place_dest = PlaceDetails::where('pd_name', 'like', '%'.$value.'%')->first();
                    
                    $relation = CodeDetails::whereRaw('(pd_id = '.$place_from->pd_id.' and pd_id_destination = '.$place_dest->pd_id.') or (pd_id = '.$place_dest->pd_id.' and pd_id_destination = '.$place_from->pd_id.')')->first();
                    
                    if (count($relation) > 0){
                        $dataf = $relation->distance;
                    }else{
                        $dataf = "∞";
                    }
                    
                    // Print Table
                    // echo "</td><td align='right'>".$dataf;//. ' > '.$key1.','.$key;//.'='.$nama[$key1][$key];
                }
                    
                $graph[$key1][] = str_replace(" km", "", str_replace(",", ".", $dataf));
            }
            $nodes[] = $value1;
            $n++;
        }

        // Print Table
        // echo "</td></tr></tbody></table>";

        $index_from =  array_search($from->pd_name, $nama_jalan);
        $index_destination =  array_search($destination->pd_name, $nama_jalan);
        
        // Print Table
        // echo "<br><br>";
        
        $return_data['get_Distance'] = $this->getDistanceOriginal($graph, $nodes, $index_from, $index_destination);
        
        // Mengholah Jalur dan Angkot yang bisa dilewati
        $jumlah_jalan = count($return_data['get_Distance']);
        $transit['place_name'] = $return_data['get_Distance'];
        
        for ($i=0; $i<$jumlah_jalan-1; $i++){
            $place_from = PlaceDetails::where('pd_name', 'like', '%'.$return_data['get_Distance'][$i].'%')->first();
            $place_dest = PlaceDetails::where('pd_name', 'like', '%'.$return_data['get_Distance'][$i+1].'%')->first();
            
            $relation = CodeDetails::whereRaw('(pd_id = '.$place_from->pd_id.' and pd_id_destination = '.$place_dest->pd_id.') or (pd_id = '.$place_dest->pd_id.' and pd_id_destination = '.$place_from->pd_id.')')->with('details_code')->get();

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

    public function getDistanceOriginal($graph, $nodes, $index_from, $index_destination){
   
        $fw = new FloydWarshall($graph, $nodes);
    
        $sp = $fw->get_path($index_from,$index_destination);
        
        // Print Table
        // echo 'Ruter Terdekat Dari Start '.$nodes[$index_from].' Ke '.$nodes[$index_destination].' Adalah: <strong>';
        
        $jl = count($sp);
        $r=1;

        $final_node = array();

        foreach ($sp as $value) {
            
            // Print Table
            // echo $nodes[$value];
            
            array_push($final_node, $nodes[$value]);
            if ($r == $jl) {
                
            }
            else{
                
                // Print Table
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