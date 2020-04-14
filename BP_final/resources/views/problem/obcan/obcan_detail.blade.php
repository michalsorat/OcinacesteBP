@extends('custom_layout.obcan.obcan_app')

@section('content')

    <script type="text/javascript">
        /**
         * Global marker object that holds all markers.
         * @type {Object.<string, google.maps.LatLng>}
         */
        var markers = {};
        var markerString;

        function initialize() {
            var loc = split(" {{ $problem->poloha }}");

            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: getLocVar(loc[0], loc[1])
            });


            addMarker(getLocVar(loc[0], loc[1]), map);
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

        $("document").ready(function () {
            /*$(".main-table tr").on("click", function() {
                $('#update-modal').modal('show')
            });*/

        });
    </script>



    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-6 col-lg-7">
                <div id="map"></div>
            </div>

            <div class="col-12 col-sm-12 col-md-6 col-lg-5">

                <ul class="detail">
                    <li id="zadane"><p class="detail-text"><span>Vytvorené dňa: </span>{{ $problem->created_at }}</p></li>
                    <li id="poloha"><p class="detail-text"><span>Poloha: </span>{{ $problem->poloha }}</p></li>
                    <li id="popis"><p class="detail-text"><span>Popis: </span>{{ $problem->popis_problemu }}</p></li>
                    <li id="kategoria"><p class="detail-text">
                            <span>Kategória: </span>{{ $problem->KategoriaProblemu['nazov'] }}</p></li>
                    <li id="stav-problemu"><p class="detail-text">
                            <span>Stav problému: </span>{{ $problem->StavProblemu['nazov'] }}</p></li>
                    <li id="stav-riesenia"><p class="detail-text">
                            <span>Stav riešenia problému: </span>{{ $stav_riesenia_problemu->TypStavuRieseniaProblemu['nazov'] }}
                        </p></li>
                    @if($popis_stavu_riesenia == null)
                        <li id="popis-stavu-riesenia-problemu"><p class="detail-text">
                                <span>Popis stavu riešenia problému: </span>Popis zatiaľ nebol priradený
                            </p></li>
                    @else
                        <li id="popis-stavu-riesenia-problemu"><p class="detail-text">
                                <span>Popis stavu riešenia problému: </span>{{$popis_stavu_riesenia->popis }}
                            </p></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

@endsection

