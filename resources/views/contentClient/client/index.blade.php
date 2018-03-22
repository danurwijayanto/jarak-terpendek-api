@extends('masterClient.index')

@section('title')
<title>Skripsi - Angkutan Kota Semarang</title>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h4 class="page-title">Rute dari :   Menuju :</h4> </div>
        
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
        <div class="col-md-12 col-lg-12 col-sm-12" style="height:900px">
            <div id="map"></div>
        </div>
    </div>
</div>


<script src="https://maps.googleapis.com/maps/api/js?key={{env('GMAPS_TOKEN')}}&callback=initMap" async defer></script>
<script>
    $( document ).ready(function() {
        // $('#map').on('click',initialize)
    });
    function initMap() {
        var myLatLng = {lat: -7.074161, lng: 110.432116};

        // Create a map object and specify the DOM element for display.
        var map = new google.maps.Map(document.getElementById('map'), {
            center: myLatLng, 
            zoom: 16
        });

        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            title: 'Hello World!'
        });
    } 

</script>
@endsection