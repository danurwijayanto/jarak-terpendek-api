@extends('master.index')

@section('title')
<title>Skripsi - Daftar Nama Tempat</title>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Daftar Nama Tempat</h4> </div>
        
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
                <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#tambahTempat">Tambah Data</button>
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
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Tempat</th>
                                <th>Longitude</th>
                                <th>Latitude</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($tempat) && count($tempat) > 0)
                            @foreach ($tempat as $list)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $list->pd_name }}</td>
                                    <td>{{ $list->pd_longitude }}</td>
                                    <td>{{ $list->pd_latitude }}</td>
                                    <td>
                                        <a href="#" class="btn btn-primary edit-tempat" role="button" data-toggle="modal" data-target="#editTempat" data-detail='<?php echo json_encode(array("pd_id" => $list->pd_id, "pd_name" => $list->pd_name,  "pd_longitude" => $list->pd_longitude,  "pd_latitude" => $list->pd_latitude)); ?>'>Rubah</a>
                                        <a href="{{ url('admin/lokasi/hapus/'.$list->pd_id) }}" class="btn btn-danger" role="button">Hapus</a>
                                    </td>
                                </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                @if (isset($tempat) && count($tempat) > 0)
                {{ $tempat->links() }}
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Angkutan-->
<div id="tambahTempat" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tambah Daftar Nama Tempat</h4>
      </div>
      <div class="modal-body">
        <form action="{{ url('/admin/lokasi') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Nama Tempat :</label>
                <input type="text" class="form-control" name="nama_tempat">
            </div>
            <div class="form-group">
                <label for="email">Longitude :</label>
                <input type="text" class="form-control" name="longitude">
            </div>
            <div class="form-group">
                <label for="email">Latitude :</label>
                <input type="text" class="form-control" name="latitude">
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
<div id="editTempat" class="modal fade" role="dialog">
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
                <label for="email">Nama Tempat Baru:</label>
                <input type="text" class="form-control" name="nama_tempat_edit" id="nama-tempat-edit">
            </div>
            <div class="form-group">
                <label for="email">Longitude Baru :</label>
                <input type="text" class="form-control" name="longitude_edit" id="longitude-edit">
            </div>
            <div class="form-group">
                <label for="email">Latitude Baru :</label>
                <input type="text" class="form-control" name="latitude_edit" id="latitude-edit">
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
        $( ".edit-tempat" ).click(function() {
            // Retreive data
            var data = $(this).data('detail');
            
            // Read Data
            var pd_id = data['pd_id'];
            var pd_name = data['pd_name'];
            var pd_longitude = data['pd_longitude'];
            var pd_latitude = data['pd_latitude'];
            console.log(data);
            // Action
            $("#nama-tempat-edit").val(pd_name);
            $("#longitude-edit").val(pd_longitude);
            $("#latitude-edit").val(pd_latitude);
            $("#modal-edit-identification").html(': '+pd_name);
            $("#modal-edit-form").attr('action', '{{ url("/admin/lokasi") }}/'+pd_id+'/edit');
        });
    });
</script>
@endsection