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
    @if (isset($home))
    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="white-box" style="min-height: 360px;">
                <div id="map-admin" class="gmaps" style="height: 450px;"></div>
            </div>
        </div>
    </div>
    @endif
    <!-- <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12" style="height:900px">
            <div id="map"></div>
        </div>
    </div> -->
</div>


<!-- GMAPS Script -->
<script src="https://maps.googleapis.com/maps/api/js?key={{env('GMAPS_TOKEN')}}&callback=initMap"async defer></script>
    
<!-- <script src="https://maps.googleapis.com/maps/api/js?key={{env('GMAPS_TOKEN')}}&callback=initMap" async defer></script> -->
<script src="{{ url('plugins/bower_components/jquery/dist/jquery.min.js') }}" ></script>
<script type="text/javascript">

    function initMap() {

        var data = '{{$lokasi}}';
        data = JSON.parse(data.replace(/&quot;/g,'"'));

        var centermap = new google.maps.LatLng(data[0]['pd_latitude'],data[0]['pd_longitude']);
        // console.log(data[0]['pd_latitude'],data[0]['pd_longitude']);
        // Create a map object and specify the DOM element
        // for display.

        var map = new google.maps.Map(document.getElementById('map-admin'), {
            center: centermap,
            zoom: 13
        });

        jQuery.each( data, function( key, value ) {
            var infowindow = new google.maps.InfoWindow();
            var markerPoint = new google.maps.LatLng(value['pd_latitude'],value['pd_longitude']);
            var placename = value['pd_name'];

            /*maker */
            marker = new google.maps.Marker({
                map:map,
                draggable:false,

                animation: google.maps.Animation.DROP,
                position: markerPoint,
                title: placename
            });

            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infowindow.setContent(placename);
                    infowindow.open(map, marker);
                }
            })(marker, key));

        });
        
        // Create a marker and set its position.
        // var marker = new google.maps.Marker({
        //     map: map,
        //     position: centermap,
        // });
    }


</script>
@endsection