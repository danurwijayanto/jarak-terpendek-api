<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\PlaceDetails;
use App\Model\CodeDetails;
use App\Traits\Algoritm;
use RFHaversini\Distance;

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
        $data['home'] = true;

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
        $process = ($this->algoritm_index($request));
        $group = array();
        $place = array();
        // $rute = array();
        $new_rute = array();
        $temporary_new_rute = array();
        $angkot = '';
        $index = 0;

        $return_data = array();
        $return_data['maps_detail'] = array();
        $get_place = PlaceDetails::orderBy("pd_name", "asc")->get();
        // dd($process);

        // Mengholah Jalur dan Angkot yang bisa dilewati (Rute)
        $jumlah_jalan = count($process['get_Distance']);
        $jumlah_jalur = 1;
        //1. Menghitung jenis jalur yang berbeda - beda
        for ($i=0; $i<$jumlah_jalan-1; $i++){
            $place_from = PlaceDetails::where('pd_name', 'like', '%'.$process['get_Distance'][$i].'%')->first();
            $place_dest = PlaceDetails::where('pd_name', 'like', '%'.$process['get_Distance'][$i+1].'%')->first();
            
            $relation = CodeDetails::whereRaw('(pd_id = '.$place_from->pd_id.' and pd_id_destination = '.$place_dest->pd_id.')')->with('details')->with('details_code')->with('details_destination')->get();
            $jumlah_relasi = count($relation);
            $jumlah_jalur = $jumlah_jalur * $jumlah_relasi;
        }
        for ($i=0; $i<$jumlah_jalur; $i++){
            $temporary_new_rute[$i] = array();
            $new_rute[$i] = array();
        }

        // 2. Memasukkan jalur ke array sesuai $jumlah
        $index_array = 0;
        for ($i=0; $i<$jumlah_jalan-1; $i++){
            $place_from = PlaceDetails::where('pd_name', 'like', '%'.$process['get_Distance'][$i].'%')->first();
            $place_dest = PlaceDetails::where('pd_name', 'like', '%'.$process['get_Distance'][$i+1].'%')->first();
            
            $relation = CodeDetails::whereRaw('(pd_id = '.$place_from->pd_id.' and pd_id_destination = '.$place_dest->pd_id.')')->with('details')->with('details_code')->with('details_destination')->get();
            $jumlah_relasi = count($relation);
            if ($jumlah_relasi > 0){
                // for ($k=0; $k<$jumlah_jalur; $k++){
                    // $new_rute[$k] = array();
                    for ($j=0; $j<$jumlah_relasi; $j++){
                        // Jika jumlah jalur lebih dari 1 
                        if ($jumlah_jalur > 1){
                            $rute = array();
                        }

                        if ($i == 0){
                            $status = 'naik';
                        }elseif($rute[$i-1]['nama_angkot'] != $relation[$j]->details_code->pc_name){
                            $status = 'pindah';
                        }

                        $rute[] = [
                            'nama_tempat' => $relation[$j]->details->pd_name,
                            'nama_angkot' => $relation[$j]->details_code->pc_name,
                            'longitude' => $relation[$j]->details_destination->pd_longitude,
                            'latitude' => $relation[$j]->details_destination->pd_latitude,
                            'status' => $status
                        ];
                        if ($i == $jumlah_jalan-2){
                            $status = 'turun';
                            $rute[] = [
                                'nama_tempat' => $relation[$j]->details_destination->pd_name,
                                'nama_angkot' => $relation[$j]->details_code->pc_name,
                                'longitude' => $relation[$j]->details_destination->pd_longitude,
                                'latitude' => $relation[$j]->details_destination->pd_latitude,
                                'status' => $status
                            ];
                        }
                        // dd($new_rute[$index_array]);
                        // for ($k=0; $k<$jumlah_jalur; $k++){
                        // Jika jumlah jalur lebih dari 1 
                        if ($jumlah_jalur > 1){
                            array_push($new_rute[$index_array],$rute);
                            if  ($jumlah_relasi < $jumlah_jalur){
                                $index_array++;
                                for ($k=$index_array; $k<$jumlah_jalur; $k++){
                                    array_push($new_rute[$index_array],$rute);
                                }
                            }
                            // dd($new_rute);
                            $index_array++;
                            // dd($jumlah_jalur);
                            if ($index_array >= $jumlah_jalur){
                                $index_array = 0;
                            }
                        }else{
                            $new_rute = $rute;
                        }
                            // }
                        // break;
                    }
                    // $new_rute[$k][] = $rute;
                // }
            }
        }

        // 2. Mengolah array $new_rute
        if ($jumlah_jalur<=1){
            foreach ($new_rute as $data){
                // dd($data);
                for ($i=0; $i<$jumlah_jalur; $i++){
                    array_push($temporary_new_rute[$i], $data);
                }
            }
        }else{
            for ($i=0; $i<$jumlah_jalur; $i++){
                foreach ($new_rute[$i] as $data){
                    foreach ($data as $subdata){
                        array_push($temporary_new_rute[$i], $subdata);
                    }
                }
            }
        }
        $new_rute = $temporary_new_rute;
        // dd($new_rute);
        // End Mengholah Jalur dan Angkot yang bisa dilewati
        foreach ($process['get_Distance'] as $data){
            $getPlace = PlaceDetails::where('pd_name', $data)->get()->toArray();
            foreach ($getPlace as $listData){
                // $place[] =  $listData;
                
                if (!empty($process['transit']['place_name'])){
                    foreach ($process['transit']['place_name'] as $transit){
                        if ($data != $transit){
                            $trayek = $process['transit']['angkot'][$index];
                            $status = '';
                        }else{
                            $index++;
                            if (isset($process['transit']['angkot'][$index])){
                                $trayek = $process['transit']['angkot'][$index];
                                $status = 'pindah';
                            }
                        }
                        $listData['nama_daerah'] = $data;
                        $listData['nama_trayek'] = $trayek;
                        $listData['status'] = $status;
                        
                        $place[] =  $listData;
                    }
                }else{
                    $trayek = isset($process['transit']['angkot'][0]) ? $process['transit']['angkot'][0] : '';
                    $status = '';
                    
                    $listData['nama_daerah'] = $data;
                    $listData['nama_trayek'] = $trayek;
                    $listData['status'] = $status;

                    $place[] =  $listData;
                }
            }
        }
        // dd($listData);
        $countPlace = count($place);

        for ($i = 0; $i <= $countPlace - 1; $i++) {
            $index_tempat = 0;
            $index_angkot = 0;

            $url = "https://maps.googleapis.com/maps/api/directions/json?";
            $origins = "origin=".$place[$i]['pd_latitude'].",".$place[$i]['pd_longitude'];
            $destination = "&destination=".$place[$i+1]['pd_latitude'].",".$place[$i+1]['pd_longitude'];
            $key = "&key=".env("GMAPS_TOKEN");
            $client = new \GuzzleHttp\Client();
            // dd($url.$origins.$destination.$key);
            $res = $client->request('GET', $url.$origins.$destination.$key);
            // dd($res->getStatusCode());
            // dd($res->getHeaderLine('content-type'));
            $res = json_decode($res->getBody(), true);
            
            $group['latitude'] = $place[$i]['pd_latitude'];
            $group['longitude'] = $place[$i]['pd_longitude'];
            
            if ($place[$i]['pd_name'] == $process['transit']['place_name'][$index_tempat]){
                $index_tempat++;
                $index_angkot++;
                if ($index <= count($process['transit']['angkot'])){
                    $group['angkot'] = $process['transit']['angkot'][$index_angkot];
                }
            }else{
                if ($index <= count($process['transit'])){
                    $group['angkot'] = $process['transit']['angkot'][$index_angkot];
                }
            }

            foreach ($res['routes'] as $a){
                $group['polyline'] = str_replace('\\','cHaNgE',$a['overview_polyline']['points']);
                array_push($return_data['maps_detail'],$group);
            }
            
        } 
        // dd($return_data['maps_detail']);
        // dd($place);
        $return_data['maps_detail'] = json_encode($return_data['maps_detail']);
        $return_data['place_detail'] = json_encode($place);
        $return_data['rute'] = json_encode($rute);
        $return_data['new_rute'] = json_encode($new_rute);
        $return_data['lokasi'] = $get_place;

        // dd($return_data);
        return view('contentClient.client.ruteTerpendek', $return_data);
    }

    public function hitungJarak(Request $request){

        
        $dari = $request->id_trayek_dari;
        $ke = $request->id_trayek_tujuan;
        
        $detail_dari = PlaceDetails::where('pd_id', $dari)->first();
        $detail_ke = PlaceDetails::where('pd_id', $ke)->first();
        
        $jarak = round(Distance::toKilometers($detail_dari->pd_latitude ,$detail_dari->pd_longitude, $detail_ke->pd_latitude, $detail_ke->pd_longitude), 2);
        
        return json_encode($jarak);
    }
}
