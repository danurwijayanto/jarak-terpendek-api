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
                <div class="col-md-3 col-sm-4 col-xs-6 pull-right">
                    <select class="form-control pull-right row b-none">
                        <option>March 2017</option>
                        <option>April 2017</option>
                        <option>May 2017</option>
                        <option>June 2017</option>
                        <option>July 2017</option>
                    </select>
                </div>
                <h3 class="box-title">Recent sales</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>NAME</th>
                                <th>STATUS</th>
                                <th>DATE</th>
                                <th>PRICE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td class="txt-oflo">Elite admin</td>
                                <td>SALE</td>
                                <td class="txt-oflo">April 18, 2017</td>
                                <td><span class="text-success">$24</span></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td class="txt-oflo">Real Homes WP Theme</td>
                                <td>EXTENDED</td>
                                <td class="txt-oflo">April 19, 2017</td>
                                <td><span class="text-info">$1250</span></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td class="txt-oflo">Ample Admin</td>
                                <td>EXTENDED</td>
                                <td class="txt-oflo">April 19, 2017</td>
                                <td><span class="text-info">$1250</span></td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td class="txt-oflo">Medical Pro WP Theme</td>
                                <td>TAX</td>
                                <td class="txt-oflo">April 20, 2017</td>
                                <td><span class="text-danger">-$24</span></td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td class="txt-oflo">Hosting press html</td>
                                <td>SALE</td>
                                <td class="txt-oflo">April 21, 2017</td>
                                <td><span class="text-success">$24</span></td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td class="txt-oflo">Digital Agency PSD</td>
                                <td>SALE</td>
                                <td class="txt-oflo">April 23, 2017</td>
                                <td><span class="text-danger">-$14</span></td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td class="txt-oflo">Helping Hands WP Theme</td>
                                <td>MEMBER</td>
                                <td class="txt-oflo">April 22, 2017</td>
                                <td><span class="text-success">$64</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
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
@endsection