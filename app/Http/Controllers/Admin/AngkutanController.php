<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\PlaceCode;
use App\Model\PlaceDetails;
use App\Model\CodeDetails;
use RFHaversini\Distance;

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
        $get_angkutan = PlaceCode::with('details')->with('details_destination')->paginate(5);
        $get_lokasi = PlaceDetails::orderBy("pd_name", "asc")->get();
        // $get_relation = CodeDetails::with('details')->with('details_code')->with('details_destination')->paginate(5);

        // Mapping
        $data['angkutan'] = $get_angkutan;
        $data['lokasi'] = $get_lokasi;
        // $data['relation'] = $get_relation;

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
        if (empty($request->trayek) || empty($request->kode_angkutan)){
            $status = "Gagal menambahkan data, harap mengisi data trayek";
            return redirect()->back()->with('alert', $status);
        }
        
        // dd($find_angkutan);
        if (PlaceCode::where('pc_name', '=', $request->kode_angkutan)->exists()){
            $status = "Gagal menambahkan data, kode angkutan/ trayek sudah terpakai";
            return redirect()->back()->with('alert', $status);
        }
        
        // Insert Data
        $angkutan = new PlaceCode;
        $angkutan->pc_name = $request->kode_angkutan;
        if ($angkutan->save()){
            $id_angkutan = $angkutan->pc_id;
            
            foreach ($request->trayek as $list){
                $decode = json_decode($list, true);

                $code_details = new CodeDetails;
                $code_details->pc_id = $id_angkutan;
                $code_details->pd_id = $decode['id_trayek_dari'];
                $code_details->pd_id_destination = $decode['id_trayek_tujuan'];
                $code_details->distance = $decode['jarak'];
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
        $trayek_id = isset($_GET['trayek_edit']) ? $_GET['trayek_edit'] : '';
        $status = '';

        // Action
        $edit = PlaceCode::where('pc_id', $id)->update(['pc_name' => $pc_name]);
        
        if (!empty($trayek_id)){
            // Hapus relasi sebelumnya
            $delete_relations = CodeDetails::where('pc_id', $id)->delete();

            // Insert relasi dengan data baru
            if (!empty($trayek_id)){
                foreach ($trayek_id as $list){
                    $list_data = json_decode($list, true);
                    
                    if (is_array($list_data)){
                        $code_details = new CodeDetails;
                        $code_details->pc_id = $id;
                        $code_details->pd_id = $list_data['id_trayek_dari'] or '';
                        $code_details->pd_id_destination = $list_data['id_trayek_tujuan'] or '';
                        $code_details->distance = $list_data['jarak'] or '';
                        $code_details->save();
                    }
                }
            }
        }else{
            // Hapus relasi semua
            $delete_relations = CodeDetails::where('pc_id', $id)->delete();
        }

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
        $delete = CodeDetails::where('pc_id', $id)->delete();

        $delete ? $status = 'Data berhasil dihapus' : $status = 'fail';

        // Return
        return redirect()->back()->with('alert', $status);
    }

}
