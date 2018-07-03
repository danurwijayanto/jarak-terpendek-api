@extends('master.index')

@section('title')
<title>Skripsi - Daftar Angkutan</title>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Daftar Kode dan Trayek Angkutan Kota</h4> </div>
        
        <!--
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
            </ol>
        </div>
        -->
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="white-box">
                <!-- Trigger the modal with a button -->
                <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#tambahAngkutan">Tambah Data</button>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- table -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="white-box">
                @if (session('alert'))
                    <div class="alert alert-warning" role="alert">
                        {{ session('alert') }}
                    </div>
                @endif
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode</th>
                                <th>Trayek Asal</th>
                                <th>Trayek Tujuan</th>
                                <th>Jarak</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($angkutan) && count($angkutan) > 0)
                            @foreach ($angkutan as $list)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $list->pc_name }}</td>
                                    <td>
                                        <ul>
                                        @foreach ($list->details as $list_detail)
                                        <li>{{ $list_detail->pd_name }}</li>
                                        @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            @foreach ($list->details_destination as $list_detail_dest)
                                            <li>{{ $list_detail_dest->pd_name }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>{{ $list->pivot }}</td>
                                    <td>
                                        <?php 
                                            $data_trayek = array();
                                            $jumlah_asal = count($list->details);
                                            $jumlah_tujuan = count($list->details_destination);
                                            for ($i=0; $i<$jumlah_asal; $i++){
                                                $data_trayek[] = [
                                                    'id_asal'=>$list->details[$i]->pd_id,
                                                    'nama_asal'=>$list->details[$i]->pd_name,
                                                    'id_tujuan'=>$list->details_destination[$i]->pd_id,
                                                    'nama_tujuan'=>$list->details_destination[$i]->pd_name,
                                                ];
                                            }

                                        ?>
                                        <a href="#" class="btn btn-primary edit-angkutan-fixed" role="button" data-toggle="modal" data-target="#editAngkutan" data-trayek='{{ json_encode($data_trayek) }}' data-detail='{{ json_encode(array("pc_id" => $list->pc_id, "pc_name" => $list->pc_name)) }}'>Rubah</a>
                                        <a href="{{ url('admin/angkutan/hapus/'.$list->pc_id) }}" class="btn btn-danger" role="button">Hapus</a>
                                    </td>
                                </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                @if (isset($angkutan) && count($angkutan) > 0)
                {{ $angkutan->links() }}
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Angkutan-->
<div id="tambahAngkutan" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tambah Daftar Angkutan Kota</h4>
      </div>
      <div class="modal-body">
        <form id="form-tambah-angkutan" action="{{ url('/admin/angkutan') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Kode Angkutan :</label>
                <input type="text" class="form-control" name="kode_angkutan" required>
            </div>
            <div class="form-group">
                <label for="select-lokasi">Trayek dari:</label>
                <!-- <select class="js-example-basic-multiple form-control" name="trayek[]" multiple="multiple"> -->
                <select id="select-lokasi" class="form-control" name="list-lokasi">
                    @if (isset($lokasi) && !empty($lokasi))
                    @foreach ($lokasi as $list)
                        <option value="{{ $list->pd_id }}">{{ $list->pd_name }}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div class="form-group">
                <label for="select-tujuan">Trayek tujuan:</label>
                <!-- <select class="js-example-basic-multiple form-control" name="trayek[]" multiple="multiple"> -->
                <select id="select-tujuan" class="form-control" name="list-tujuan">
                    @if (isset($lokasi) && !empty($lokasi))
                    @foreach ($lokasi as $list)
                        <option value="{{ $list->pd_id }}">{{ $list->pd_name }}</option>
                    @endforeach
                    @endif
                </select>
                <button type="button" class="btn btn-default tambah-trayek">Tambah</button>
            </div>
            <div class="form-group">
                <label for="jarak">Jarak (Km) :</label>
                <input id="jarak-trayek" type="text" class="form-control" name="jarak_trayek" readonly>
            </div>
            <div class="form-group">
                <table class="table" id="tabel-trayek">
                    <thead>
                        <tr>
                            <th>Trayek dari</th>
                            <th>Trayek tujuan</th>
                            <th>Jarak (Km)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tabel-trayek-body">
                    </tbody>
                </table>
            </div>
            <button type="submit" class="btn btn-default">Simpan</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- Modal Edit Angkutan-->
<div id="editAngkutan" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Data Angkutan Kota <span id="modal-edit-identification"></span></h4>
      </div>
      <div class="modal-body">
        <form action="#" method="GET" id="modal-edit-form">
            @csrf
            <div class="form-group">
                <label for="email">Kode Angkutan Baru :</label>
                <input type="text" class="form-control" name="kode_angkutan_edit" id="kode-angkutan-edit">
            </div>
            <div class="form-group">
                <label for="email">Trayek dari:</label>
                <!-- <select id="js-example-basic-multiple-edit" class="js-example-basic-multiple form-control" name="trayek_edit[]" multiple="multiple"> -->
                <select id="select-lokasi-edit" class="form-control" name="list-lokasi-edit">    
                    @if (isset($lokasi) && !empty($lokasi))
                    @foreach ($lokasi as $list)
                        <option value="{{ $list->pd_id }}">{{ $list->pd_name }}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div class="form-group">
                <label for="select-tujuan">Trayek tujuan:</label>
                <!-- <select class="js-example-basic-multiple form-control" name="trayek[]" multiple="multiple"> -->
                <select id="select-tujuan" class="form-control" name="list-tujuan">
                    @if (isset($lokasi) && !empty($lokasi))
                    @foreach ($lokasi as $list)
                        <option value="{{ $list->pd_id }}">{{ $list->pd_name }}</option>
                    @endforeach
                    @endif
                </select>
                <button type="button" class="btn btn-default tambah-trayek-edit">Tambah</button>
            </div>
            <div class="from-group">
                <table class="table" id="tabel-trayek-edit">
                    <thead>
                        <tr>
                            <th>Trayek dari</th>
                            <th>Trayek tujuan</th>
                            <th>Jarak (Km)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tabel-trayek-body-edit">
                    </tbody>
                </table>
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<script>
    $( document ).ready(function() {
        $('.tambah-trayek').click(function() {
            var id_trayek_dari = $("#select-lokasi :selected").val();
            var id_trayek_tujuan = $("#select-tujuan :selected").val();
            var trayek_dari = $("#select-lokasi :selected").text();
            var trayek_tujuan = $("#select-tujuan :selected").text();
            
            var formData = {
                id_trayek_dari: id_trayek_dari,
                id_trayek_tujuan: id_trayek_tujuan,
            }
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN':'{{csrf_token()}}'
                }
            });

            // Kalkulasi jarak
            $.ajax({
                type:'POST',
                url:'{{ url("/kalkukasi/hitungjarak") }}',
                data:formData,
                success:function(data){
                    $("#jarak-trayek").val(data);
                    
                    var obj = { 
                        "id_trayek_dari":id_trayek_dari, 
                        "id_trayek_tujuan":id_trayek_tujuan, 
                        "jarak":data
                    };

                    $('<input>').attr({
                        type: 'hidden',
                        id: id_trayek_dari+'_'+id_trayek_tujuan,
                        name: 'trayek[]',
                        value: JSON.stringify(obj)
                    }).appendTo('#form-tambah-angkutan');
                    
                    $("#tabel-trayek-body").append('<tr><td>'+trayek_dari+'</td><td>'+trayek_tujuan+'</td><td>'+data+'</td><td><a href="#" data-id='+id_trayek_dari+'_'+id_trayek_tujuan+' class="hapus-trayek">Hapus</a></td></tr>');
                }
            });

        });

        $('.tambah-trayek-edit').click(function() {
            var id_trayek = $("#select-lokasi-edit :selected").val();
            var trayek = $("#select-lokasi-edit :selected").text();
            $('<input>').attr({
                type: 'hidden',
                id: 'trayek-edit-'+id_trayek,
                name: 'trayek_edit[]',
                value: id_trayek
            }).appendTo('#modal-edit-form');
            
            $("#tabel-trayek-body-edit").append('<tr><td>'+trayek+'</td><td><a href="#" data-id='+id_trayek+' class="hapus-trayek-edit">Hapus</a></td></tr>');
        });
        
        $('#tabel-trayek-body').on('click', '.hapus-trayek', function() {
            var id_trayek = $(this).data('id'); 
            $(this).parent().parent().remove();
            $('#trayek_'+id_trayek).remove();
        });

        $('#tabel-trayek-body-edit').on('click', '.hapus-trayek-edit', function() {
            var id_trayek = $(this).data('id'); 
            $(this).parent().parent().remove();
            $('#trayek-edit-'+id_trayek).remove();
        });

        $('.js-example-basic-multiple').select2({
            width: '100%',
        });
        
        $( ".edit-angkutan" ).click(function() {
            // Retreive data
            var data = $(this).data('detail');
            var data_trayek = $(this).data('trayek');
            var selected = [];
                                        
            // Read Data
            var pc_id = data['pc_id'];
            var pc_name = data['pc_name'];
            
            // Action
            $.each( data_trayek, function( key, value ) {
                selected.push(value['pd_id']);
            });
            $('#js-example-basic-multiple-edit').val(selected);
            $('#js-example-basic-multiple-edit').trigger('change');
            
            $("#kode-angkutan-edit").val(pc_name);
            $("#modal-edit-identification").html(': '+pc_name);
            $("#modal-edit-form").attr('action', '{{ url("/admin/angkutan") }}/'+pc_id+'/edit');
        });

        $( ".edit-angkutan-fixed" ).click(function() {
            // Retreive data
            var data = $(this).data('detail');
            var data_trayek = $(this).data('trayek');
            var selected = [];
                                        
            // Read Data
            var pc_id = data['pc_id'];
            var pc_name = data['pc_name'];
            
            // Action
            $('.row-append').remove();
            $.each( data_trayek, function( key, value ) {
                selected.push(value['id_asal']);
                $("#tabel-trayek-body-edit").append('<tr class="row-append"><td>'+value['nama_asal']+'</td><td>'+value['nama_tujuan']+'</td><td></td><td><a href="#" data-id='+value['id_asal']+' class="hapus-trayek-edit">Hapus</a></td></tr>');
                $('<input>').attr({
                    type: 'hidden',
                    id: 'trayek-edit-'+value['id_asal'],
                    name: 'trayek_edit[]',
                    value: value['id_asal']
                }).appendTo('#modal-edit-form');
            });
            
            $("#kode-angkutan-edit").val(pc_name);
            $("#modal-edit-identification").html(': '+pc_name);
            $("#modal-edit-form").attr('action', '{{ url("/admin/angkutan") }}/'+pc_id+'/edit');
        });
    });
</script>
@endsection