<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\PlaceCode;
use App\Model\PlaceDetails;
use App\Model\CodeDetails;

class AngkutanController extends Controller
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
        $get_angkutan = PlaceCode::paginate(5);
        $get_lokasi = PlaceDetails::get();

        // Mapping
        $data['angkutan'] = $get_angkutan;
        $data['lokasi'] = $get_lokasi;

        return view('content.angkutan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return 'create';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $status = 'Gagal';
        
        // Insert Data
        $angkutan = new PlaceCode;
        $angkutan->pc_name = $request->kode_angkutan;
        if ($angkutan->save()){
            $id_angkutan = $angkutan->pc_id;
            
            foreach ($request->trayek as $list => $key){
                $code_details = new CodeDetails;
                $code_details->pc_id = $id_angkutan;
                $code_details->pd_id = $key;
                $code_details->save();
            }

            $status = "Data berhasil ditambah";
        }
        
        /* Insert with Query Builder
        $insert = PlaceCode::insert(
            ['pc_name' => $data['kode_angkutan']]
        );
        $insert ? $status = 'Data berhasil ditambah' : $status = 'fail';
        */


        // Return
        return redirect()->back()->with('alert', $status);

        //return view('content.angkutan.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return 'show';
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
        $pc_name = isset($_GET['kode_angkutan_edit']) ? $_GET['kode_angkutan_edit'] : '';
        $status = '';

        // Action
        $edit = PlaceCode::where('pc_id', $id)->update(['pc_name' => $pc_name]);

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
        return 'update';
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
        $delete = PlaceCode::where('pc_id', $id)->delete();

        $delete ? $status = 'Data berhasil dihapus' : $status = 'fail';

        // Return
        return redirect()->back()->with('alert', $status);
    }
}
