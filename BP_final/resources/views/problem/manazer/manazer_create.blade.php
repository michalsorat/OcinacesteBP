@extends('custom_layout.manazer.manazer_app')

@section('content')

    <script type="text/javascript">
        /**
         * Global marker object that holds all markers.
         * @type {Object.<string, google.maps.LatLng>}
         */
        var markers = [];
        var markerString;
        var markersCount = 0;

        function initialize() {
            var trnava = {lat: 48.3767994, lng: 17.5835082};

            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: trnava
            });

            // This event listener calls addMarker() when the map is clicked.
            google.maps.event.addListener(map, 'click', function (event) {

                if (markersCount < 1) {

                    var lat = event.latLng.lat();
                    var lng = event.latLng.lng();

                    markerString = getMarkerString(lat, lng);
                    addMarker(event.latLng, map);

                    document.getElementById('poloha').value = markerString;
                } else window.alert("Môžete pridať iba jednu polohu. " +
                    "Pre odstránenie polohy z mapy, kliknite pravým tlačidlom myše na označené miesto");

            });
        }

        google.maps.event.addDomListener(window, 'load', initialize);


        /**
         * Concatenates given lat and lng with an comma and returns it.
         * @param {!number} lat Latitude.
         * @param {!number} lng Longitude.
         * @return {string} Concatenated marker id.
         */
        var getMarkerString = function (lat, lng) {

            return lat.toFixed(6) + ',' + lng.toFixed(6);
        };

        /**
         * Creates an instance of google.maps.LatLng by given lat and lng values and returns it.
         * This function can be useful for getting new coordinates quickly.
         * @param {!number} lat Latitude.
         * @param {!number} lng Longitude.
         * @return {google.maps.LatLng} An instance of google.maps.LatLng object
         */
        var getLatLng = function (lat, lng) {
            return new google.maps.LatLng(lat, lng);
        };


        // Adds a marker to the map.
        function addMarker(location, map) {
            // Add the marker at the clicked location, and add the next-available label
            // from the array of alphabetical characters.
            var marker = new google.maps.Marker({
                position: location,
                animation: google.maps.Animation.DROP,
                map: map,
            });

            bindMarkerEvents(marker); // bind right click event to marker
            markersCount++;

        }


        function bindMarkerEvents(marker) {
            google.maps.event.addListener(marker, "rightclick", function (point) {
                markersCount--;
                removeMarker(marker); // remove it
            });
        }


        function removeMarker(marker) {
            marker.setMap(null); // set markers setMap to null to remove it from map
            document.getElementById('poloha').value = "";
        }
    </script>

    <section class="main-container h-100">
        <div class="container-fluid h-100">
            <div class="row h-100">

                <div class="col-12 col-sm-12 col-md-6 col-lg-7">
                    <div id="map"></div>
                </div>

                <div class="col-12 col-sm-12 col-md-6 col-lg-5">
                    <div class="container">
                        <div class="row">

                            <div class="col-12">
                                <h1 class="text-center">Vytvorenie hlásenia</h1>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="col-12 d-flex justify-content-center p-0">

                                <form class="start-form" action="{{ route('problem.store') }}" method="post">

                                    @csrf
                                    <div class="w-100 mb-2">
                                        <label for="poloha"><b>Poloha *</b></label>
                                        <input id="poloha" class="form-input" type="text" name="poloha" value="">
                                    </div>

                                    <div class="w-100 mb-2">
                                        <label for="kategoria"><b>Kategória *</b></label>
                                        <select id="kategoria" class="form-select form-input"
                                                name="kategoria_problemu_id">
                                            @foreach($kategorie as $kategoria)

                                                <option
                                                    value="{{ $kategoria->kategoria_problemu_id }}">{{ $kategoria->nazov }}</option>
                                            @endforeach
                                        </select>

                                    </div>

                                    <div class="w-100 mb-2">
                                        <label for="stav_problemu"><b>Stav problému *</b></label>
                                        <select id="stav_problemu" class="form-select form-input"
                                                name="stav_problemu_id">
                                            @foreach($stavy as $stav)

                                                <option
                                                    value="{{ $stav->stav_problemu_id }}">{{ $stav->nazov }}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="w-100 mb-2">
                                        <label for="popis_problemu"><b>Popis problému *</b></label>
                                        <textarea id="popis_problemu" rows="6" class="form-input"
                                                  name="popis_problemu"></textarea>
                                    </div>
                                    <!--
                                                                        <div class="w-100 mb-2">
                                                                            <label for="fotka">Vložiť fotku</label>
                                                                            <input id="fotka" type="file">
                                                                        </div>
                                                                        -->
                                    <button type="submit" class="btn btn-primary" name="submit">Vytvorit</button>
                                </form>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
