@extends('masterClient.index')

@section('title')
<title>Skripsi - Angkutan Kota Semarang</title>
@endsection

@section('content')

<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?php

                if (!empty($rute)){
                    $rute = json_decode($rute, true);
                    $count = count($rute);
                    $from_destination = $rute[0]['nama_tempat'];
                    $to_destination = $rute[$count - 1]['nama_tempat'];
                }
            ?>
            <h3>Rute dari : {{ $from_destination }}  menuju : {{ $to_destination }}</h3> </div>
        
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
    <?php
    $index_rute = 0;
    $new_rute = json_decode($new_rute, true);
    if (count($new_rute>0)){   
    foreach ($new_rute as $list){
    ?>
    <!-- panggil javascript saat halaman dibuka -->
    <!-- <body onload="initialize_{{$index_rute}}()"> -->
    <!-- ukuran lebar peta 100 dan tinggi 400px-->
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box" style="min-height: 160px;">
                <div class="col-sm-4">
                    <div id="map_canvas_{{$index_rute}}" class="gmaps map_canvas_class"></div>
                </div>
                <div class="col-sm-8">
                    <?php
                        if (isset($list)){
                            $index = 0;
                            $count_new_route = count($list);
                            foreach ($list as $a){
                                if ($index == 0){
                                    echo "Naik ".$a['nama_angkot']." di ".$a['nama_tempat']."<br>";
                                }
                                if ($a['status'] == "pindah"){
                                    echo "Kemudian turun di ".$a['nama_tempat']."<br>";
                                    echo "Kemudian naik ".$a['nama_angkot']."<br>";
                                }
                                if ($index == $count_new_route-1){
                                    echo "Kemudian turun dan sampai di  ".$a['nama_tempat']."<br>";
                                }
                                $index++;  
                            }     
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    $index_rute++;
    }
    }
    ?>
</div>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=geometry&key={{env('GMAPS_TOKEN')}}"></script>
<!-- <script src="https://maps.googleapis.com/maps/api/js?key={{env('GMAPS_TOKEN')}}&callback=initMap" async defer></script> -->
<script src="{{ url('plugins/bower_components/jquery/dist/jquery.min.js') }}" ></script>
<script type="text/javascript">
    /* tentukan lokasi tengah peta,
    titik awal tugu dan titik akhir ugm */
    var iconBase = 'https://maps.google.com/mapfiles/kml/shapes/';

    var data = '{{$maps_detail}}';
    data = data.replace('cHaNgE',"\\\\");
    data = JSON.parse(data.replace(/&quot;/g,'"'));

    // var node = '{{json_encode($new_rute, true)}}';
    // node = JSON.parse(node.replace(/&quot;/g,'"'));

    var tengahpeta = new google.maps.LatLng(data[0]['latitude'],data[0]['longitude']);

    // Functi untuk decoding level encoding polyline 
    function decodeLevels(encodedLevelsString) {
        var decodedLevels = [];
        for (var i = 0; i < encodedLevelsString.length; ++i) {
            var level = encodedLevelsString.charCodeAt(i) - 63;
            decodedLevels.push(level);
        }

        return decodedLevels;
    }
    /* fungsi inisialisasi peta, dipanggil di body onload*/
    <?php
    $index_rute_js = 0;
    if (count($new_rute>0)){   
        foreach ($new_rute as $list){
    ?>
        var node = '{{json_encode($list, true)}}';
        node = JSON.parse(node.replace(/&quot;/g,'"'));

        var mapOptions_{{$index_rute_js}} = {
            zoom: 15,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            center: tengahpeta
        };

        var map_{{$index_rute_js}} = new google.maps.Map(document.getElementById("map_canvas_{{$index_rute_js}}"),mapOptions_{{$index_rute_js}});
        
        jQuery.each( node, function( key, value ) {

            var markerPoint = new google.maps.LatLng(value['latitude'],value['longitude']);
            
            var infowindow = new google.maps.InfoWindow();
            
            var information = value['nama_tempat']+'<br>';
            if (value['status']=='pindah'){
                information += 'Pindah trayek '+value['nama_angkot']+'<br>';
                icon = 'caution.png';
            }else if(value['status']=='tetap'){
                information += 'Angkot tetap '+value['nama_angkot']+'<br>';
                icon = 'cabs.png';
            }else if (value['status']=='naik'){
                information += 'Naik trayek '+value['nama_angkot']+'<br>';
                icon = 'cabs.png';
            }else{
                information += 'Turun trayek '+value['nama_angkot']+'<br>';
                icon = 'cabs.png';
            }
            /*maker */
            var marker = new google.maps.Marker({
                map:map_{{$index_rute_js}},
                draggable:false,

                animation: google.maps.Animation.DROP,
                position: markerPoint,
                icon: iconBase + icon
            });
            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infowindow.setContent(information);
                    infowindow.open(map_{{$index_rute_js}}, marker);
                }
            })(marker, key));
        });

        jQuery.each( data, function( key, value ) {
            /* ini adalah data polyline yang kita buat dengan polyline encoder utility */
            var str = value['polyline'];
            var array = google.maps.geometry.encoding.decodePath(str);  //str contains the encoded string from the db
            var levels = decodeLevels("BBBBBBBBBBB");
        
            /* buat parameter dari polyline seperti warna dan ketebalan garis */
            var Poly = new google.maps.Polyline({
                path: array,
                levels: levels,
                strokeColor: 'F21111',
                strokeOpacity: 1.0,
                strokeWeight: 3
            });

            /* gambarkan polyline di peta */
            Poly.setMap(map_{{$index_rute_js}});
        });
    <?php
        $index_rute_js++;
        }
    }
    ?>
  
</script>

@endsection