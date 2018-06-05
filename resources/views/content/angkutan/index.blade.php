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
                                <th>Trayek</th>
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
                                        <a href="#" class="btn btn-primary edit-angkutan-fixed" role="button" data-toggle="modal" data-target="#editAngkutan" data-trayek='{{ json_encode($list->details) }}' data-detail='{{ json_encode(array("pc_id" => $list->pc_id, "pc_name" => $list->pc_name)) }}'>Rubah</a>
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
                <label for="email">Trayek :</label>
                <!-- <select class="js-example-basic-multiple form-control" name="trayek[]" multiple="multiple"> -->
                <select id="select-lokasi" class="form-control" name="list-lokasi">
                    @if (isset($lokasi) && !empty($lokasi))
                    @foreach ($lokasi as $list)
                        <option value="{{ $list->pd_id }}">{{ $list->pd_name }}</option>
                    @endforeach
                    @endif
                </select>
                <button type="button" class="btn btn-default tambah-trayek">Tambah</button>
                <table class="table" id="tabel-trayek">
                    <thead>
                        <tr>
                            <th>Trayek</th>
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
                <label for="email">Trayek :</label>
                <!-- <select id="js-example-basic-multiple-edit" class="js-example-basic-multiple form-control" name="trayek_edit[]" multiple="multiple"> -->
                <select id="select-lokasi-edit" class="form-control" name="list-lokasi-edit">    
                    @if (isset($lokasi) && !empty($lokasi))
                    @foreach ($lokasi as $list)
                        <option value="{{ $list->pd_id }}">{{ $list->pd_name }}</option>
                    @endforeach
                    @endif
                </select>
                <button type="button" class="btn btn-default tambah-trayek-edit">Tambah</button>
                <table class="table" id="tabel-trayek-edit">
                    <thead>
                        <tr>
                            <th>Trayek</th>
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
            var id_trayek = $("#select-lokasi :selected").val();
            var trayek = $("#select-lokasi :selected").text();
            $('<input>').attr({
                type: 'hidden',
                id: 'trayek_'+id_trayek,
                name: 'trayek[]',
                value: id_trayek
            }).appendTo('#form-tambah-angkutan');
            
            $("#tabel-trayek-body").append('<tr><td>'+trayek+'</td><td><a href="#" data-id='+id_trayek+' class="hapus-trayek">Hapus</a></td></tr>');
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
            $.each( data_trayek, function( key, value ) {
                selected.push(value['pd_id']);
                $("#tabel-trayek-body-edit").append('<tr><td>'+value['pd_name']+'</td><td><a href="#" data-id='+value['pd_id']+' class="hapus-trayek-edit">Hapus</a></td></tr>');
                $('<input>').attr({
                    type: 'hidden',
                    id: 'trayek-edit-'+value['pd_id'],
                    name: 'trayek_edit[]',
                    value: value['pd_id']
                }).appendTo('#modal-edit-form');
            });
            
            $("#kode-angkutan-edit").val(pc_name);
            $("#modal-edit-identification").html(': '+pc_name);
            $("#modal-edit-form").attr('action', '{{ url("/admin/angkutan") }}/'+pc_id+'/edit');
        });
    });
</script>
@endsection