@extends('master.index')

@section('title')
<title>Skripsi - Daftar Angkutan</title>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Angkutan Kota</h4> </div>
        
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
                    <div class="alert alert-success" role="alert">
                        {{ session('alert') }}
                    </div>
                @endif
                <h3 class="box-title">Data Angkutan</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($angkutan as $list)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $list->pc_name }}</td>
                                    <td>
                                        <a href="#" class="btn btn-primary edit-angkutan" role="button" data-toggle="modal" data-target="#editAngkutan" data-detail='<?php echo json_encode(array("pc_id" => $list->pc_id, "pc_name" => $list->pc_name)); ?>'>Rubah</a>
                                        <a href="{{ url('admin/angkutan/hapus/'.$list->pc_id) }}" class="btn btn-danger" role="button">Hapus</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $angkutan->links() }}
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
        <h4 class="modal-title">Tambah Data Angkutan Kota</h4>
      </div>
      <div class="modal-body">
        <form action="{{ url('/admin/angkutan') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Kode Angkutan :</label>
                <input type="text" class="form-control" name="kode_angkutan">
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
                <input type="text" class="form-control" name="kode_angkutan_edit">
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
        $( ".edit-angkutan" ).click(function() {
            // Retreive data
            var data = $(this).data('detail');
            
            // Read Data
            var pc_id = data['pc_id'];
            var pc_name = data['pc_name'];
            
            // Action
            $("#modal-edit-identification").html(': '+pc_name);
            $("#modal-edit-form").attr('action', '{{ url("/admin/angkutan") }}/'+pc_id+'/edit');

            console.log(pc_id);
        });
    });
</script>
@endsection