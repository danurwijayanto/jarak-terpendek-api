
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<title>Google Maps JavaScript API v3 </title>
 <meta charset="utf-8">

<link href="https://developers.google.com/maps/documentation/javascript/examples/default.css" rel="stylesheet">
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=geometry&key={{env('GMAPS_TOKEN')}}"></script>
<!-- <script src="https://maps.googleapis.com/maps/api/js?key={{env('GMAPS_TOKEN')}}&callback=initMap" async defer></script> -->
<script src="{{ url('plugins/bower_components/jquery/dist/jquery.min.js') }}" ></script>
<script type="text/javascript">
    /* tentukan lokasi tengah peta,
    titik awal tugu dan titik akhir ugm */

    var data = '{{$maps_detail}}';
    data = JSON.parse(data.replace(/&quot;/g,'"'));

    var tengahpeta = new google.maps.LatLng(-7.78005,110.371715);

    var marker;
    var map;

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
    function initialize() {
        var mapOptions = {
            zoom: 15,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            center: tengahpeta
        };

        map = new google.maps.Map(document.getElementById("map_canvas"),mapOptions);
        
        
        jQuery.each( data, function( key, value ) {
            console.log(value['latitude']);
            var markerPoint = new google.maps.LatLng(value['latitude'],value['longitude']);

            /*maker */
            marker = new google.maps.Marker({
                map:map,
                draggable:false,

                animation: google.maps.Animation.DROP,
                position: markerPoint
            });

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
            Poly.setMap(map);
        });
    }
    

    // var tengahpeta = new google.maps.LatLng(-7.78005,110.371715);
    // var patungKuda = new google.maps.LatLng(-7.048588, 110.419762);
    // var setiaBudi = new google.maps.LatLng(-7.064537,110.412407);
    // var perintisKemerdekaan = new google.maps.LatLng(-7.092464,110.4092);

    // var marker;
    // var map;

    // // Functi untuk decoding level encoding polyline 
    // function decodeLevels(encodedLevelsString) {
    //     var decodedLevels = [];
    //     for (var i = 0; i < encodedLevelsString.length; ++i) {
    //         var level = encodedLevelsString.charCodeAt(i) - 63;
    //         decodedLevels.push(level);
    //     }

    //     return decodedLevels;
    // }
    // /* fungsi inisialisasi peta, dipanggil di body onload*/
    // function initialize() {
    //     var mapOptions = {
    //         zoom: 15,
    //         mapTypeId: google.maps.MapTypeId.ROADMAP,
    //         center: tengahpeta
    //     };

    //     map = new google.maps.Map(document.getElementById("map_canvas"),mapOptions);
    
    //     /*maker di stasiun ugu */
    //     marker = new google.maps.Marker({
    //         map:map,
    //         draggable:false,

    //         animation: google.maps.Animation.DROP,
    //         position: patungKuda
    //     });

    //     /* marker di ugm */
    //     marker1 = new google.maps.Marker({
    //         map:map,
    //         draggable:false,

    //         animation: google.maps.Animation.DROP,
    //         position: setiaBudi
    //     });

    //     /* marker di ugm */
    //     marker3 = new google.maps.Marker({
    //         map:map,
    //         draggable:false,

    //         animation: google.maps.Animation.DROP,
    //         position: perintisKemerdekaan
    //     });
    
    // /* ini adalah data polyline yang kita buat dengan polyline encoder utility */
    // var str="tt_j@kj}`T~ElCp@\tA|@lEpDbAv@~CfBfFtBd@NvGtBrEpAlIjB`Dn@RDZ?lARrD|@rDjAdCx@lAZnDdA`Dp@v@Tl@J";
    // var array = google.maps.geometry.encoding.decodePath(str);  //str contains the encoded string from the db
    // var levels = decodeLevels("BBBBBBBBBBB");
    // var str2="jxbj@q|{`TpEl@jGl@`In@lF^dCTlBV`BNpBL|A@fFGjGCtBOf@?xDC`BIfBOfCGnB?dAJzJ`AfFl@n@L|Bl@fAd@rFfD~BpAdBp@^JfBRxEAbAExCE~FEdD?pAGdBYpBa@DA";
    // var array2 = google.maps.geometry.encoding.decodePath(str2);  //str contains the encoded string from the db
    // var levels2 = decodeLevels("BBBBBBBBBBB");

	// /* buat parameter dari polyline seperti warna dan ketebalan garis */
    // var Poly = new google.maps.Polyline({
    //     path: array,
    //     levels: levels,
    //     strokeColor: 'F21111',
    //     strokeOpacity: 1.0,
    //     strokeWeight: 3
    // });

    // var Poly2 = new google.maps.Polyline({
    //     path: array2,
    //     levels: levels2,
    //     strokeColor: 'F21111',
    //     strokeOpacity: 1.0,
    //     strokeWeight: 3
    // });

	// /* gambarkan polyline di peta */
    // Poly.setMap(map);
    // Poly2.setMap(map);

    
//   }

  
</script>
</head>
<!-- panggil javascript saat halaman dibuka -->
<body onload="initialize()">
<!-- ukuran lebar peta 100 dan tinggi 400px-->
<div id="map_canvas" style="width:100%; height: 600px;">map div</div>
</body>
</html>