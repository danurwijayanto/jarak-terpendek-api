<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\PlaceDetails;

class LokasiController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Define
        $data = array();
        
        // Get Data
        $get_place = PlaceDetails::paginate(5);

        // Mapping
        $data['tempat'] = $get_place;

        return view('content.lokasi.index', $data);
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
        $data = $request->all();
        $status = '';

        $insert = PlaceDetails::insert([
            'pd_name' => $data['nama_tempat'],
            'pd_longitude' => $data['longitude'],
            'pd_latitude' => $data['latitude']
        ]);
        
        $insert ? $status = 'Data berhasil ditambah' : $status = 'fail';

        // Return
        return redirect()->back()->with('alert', $status);

        //return view('content.lokasi.index');
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
        // Variable
        $id = !empty($id) ? $id : '';
        $pd_name = isset($_GET['nama_tempat_edit']) ? $_GET['nama_tempat_edit'] : '';
        $pd_longitude = isset($_GET['longitude_edit']) ? $_GET['longitude_edit'] : '';
        $pd_latitude = isset($_GET['latitude_edit']) ? $_GET['latitude_edit'] : '';
        $status = '';

        // Action
        $edit = PlaceDetails::where('pd_id', $id)->update([
            'pd_name' => $pd_name,
            'pd_longitude' => $pd_longitude,
            'pd_latitude' => $pd_latitude
        ]);

        $edit ? $status = 'Data berhasil dirubah' : $status = 'fail';

        // Return
        return redirect()->back()->with('alert', $status);
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
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Variable
        $id = !empty($id) ? $id : '';
        $status = '';

        // Action
        $delete = PlaceDetails::where('pd_id', $id)->delete();

        $delete ? $status = 'Data berhasil dihapus' : $status = 'fail';

        // Return
        return redirect()->back()->with('alert', $status);
    }
}
