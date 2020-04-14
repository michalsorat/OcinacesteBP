@extends('custom_layout.dispecer.dispecer_app')

@section('content')


    <script type="text/javascript">
        /**
         * Global marker object that holds all markers.
         * @type {Object.<string, google.maps.LatLng>}
         */
        var markerString;


        function initialize() {
            var trnava = {lat: 48.3767994, lng: 17.5835082};


            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: trnava
            });

                @foreach($problems as $problem)

            var loc = split(" {{ $problem->poloha }} ");
            addMarker(getLocVar(loc[0], loc[1]), map);

            @endforeach


        }

        google.maps.event.addDomListener(window, 'load', initialize);

        /**
         * Creates an instance of google.maps.LatLng by given lat and lng values and returns it.
         * This function can be useful for getting new coordinates quickly.
         * @param {!number} lat Latitude.
         * @param {!number} lng Longitude.
         * @return {google.maps.LatLng} An instance of google.maps.LatLng object
         */
        function getLocVar(lat, lng) {

            return new google.maps.LatLng(parseFloat(lat), parseFloat(lng));

        }

        function split(str) {
            var res = str.split(",");

            return res;
        }

        // Adds a marker to the map.
        function addMarker(location, map) {
            // Add the marker at the clicked location, and add the next-available label
            // from the array of alphabetical characters.
            var marker = new google.maps.Marker({
                position: location,
                animation: google.maps.Animation.DROP,
                map: map,

            });
        }


    </script>

    <section class="main-container h-100">
        <div class="container-fluid h-100">
            <div class="row h-100">

                <div class="col-12">
                    <h1 class="text-center">Mapa všetkých hlásení</h1>
                </div>

                <!-- mapa -->
                <div class="col-12 h-500">
                    <!--<input id="pac-input" class="controls" type="text" placeholder="Search Box">-->
                    <div id="map"></div>
                </div>
                <!-- mapa -->


            </div>
        </div>
    </section>

@endsection

