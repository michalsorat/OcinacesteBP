@extends('custom_layout.welcomePage.welcomePage_app')

@section('content')

    <script type="text/javascript">

        function initAutocomplete() {
            var trnava = {lat: 48.3767994, lng: 17.5835082};

            //all problems
            var map = new google.maps.Map(document.getElementById('map'), {
                center: trnava,
                zoom: 11,
                mapTypeId: 'roadmap'
            });


            var JSARR = <?php echo json_encode($stavy_riesenia); ?>;
            var popisyArr = <?php echo json_encode($popisyArr); ?>;


            let poc = 0;
            let nazov_typu_riesenia;
            let popis_stavu_riesenia;

            @foreach($problems as $problem)

            var loc = split(" {{ $problem->poloha }}");


            @foreach($typy_stavov_riesenia as $typ)

            if (JSARR[poc] == "{{$typ->typ_stavu_riesenia_problemu_id}}") {
                nazov_typu_riesenia = "{{$typ->nazov}}"
            }

            @endforeach

                @foreach($popisyAll as $popis)

            if (popisyArr[poc] == 0) {

                popis_stavu_riesenia = "Nepriradený popis"

            } else if (popisyArr[poc] == "{{$popis->popis_stavu_riesenia_problemu_id}}") {

                popis_stavu_riesenia = "{{$popis->popis}}"


            }
            @endforeach

                poc++;
            addMarker(getLocVar(loc[0], loc[1]), map, "{{ $problem->created_at}}",
                "{{ $problem->address }}", "{{ $problem->popis_problemu }}",
                "{{ $problem->KategoriaProblemu['nazov'] }}",
                "{{ $problem->StavProblemu['nazov'] }}",
                nazov_typu_riesenia, "{{$problem->problem_id}}", popis_stavu_riesenia);

            @endforeach

            // Create the search box and link it to the UI element.
            var input = document.getElementById('pac-input');
            var searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

            // Bias the SearchBox results towards current map's viewport.
            map.addListener('bounds_changed', function () {
                searchBox.setBounds(map.getBounds());
            });


            var markers = [];
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener('places_changed', function () {
                var places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }

                // Clear out the old markers.
                markers.forEach(function (marker) {
                    marker.setMap(null);
                });
                markers = [];

                // For each place, get the icon, name and location.
                var bounds = new google.maps.LatLngBounds();
                places.forEach(function (place) {
                    if (!place.geometry) {
                        console.log("Returned place contains no geometry");
                        return;
                    }

                    // Create a marker for each place.
                    markers.push(new google.maps.Marker({
                        map: map,
                        title: place.name,
                        position: place.geometry.location,
                        icon: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png"
                    }));

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });

            var contentString = document.getElementById('create-form');
            // This event listener calls addMarker1() when the map is clicked.
            google.maps.event.addListener(map, 'click', function (event) {

                if (markersCount < 1) {
                    let lat = event.latLng.lat();
                    let lng = event.latLng.lng();
                    const KEY = "AIzaSyCkf657xylGn2KpKWraX_nLq0XHu6OghgQ";
                    const url = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lng}&key=${KEY}`;
                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            const addressFull = data.results[0].formatted_address;
                            let address = "";
                            const addressArr = addressFull.split(",");
                            for (let i = 0; i < 2; i++)
                            {
                                address += addressArr[i];
                                if (i !== 1)
                                    address += ",";
                            }
                            document.getElementById('address').value = address;
                        })

                    contentString.style.display = "block";
                    var infowindow = new google.maps.InfoWindow({
                        content: contentString
                    });

                    markerString = getMarkerString(lat, lng);
                    addMarker1(event.latLng, map, infowindow);

                    document.getElementById('poloha').value = markerString;
                } else window.alert("Môžete vytvoriť iba jeden problém súčasne. " +
                    "Pre odstránenie označenia z mapy, kliknite pravým tlačidlom myše na označené miesto." +
                    " Následne môžete vytvoriť nové označenie.");

            })
            //
            // map1.addListener('bounds_changed', function () {
            //     searchBox1.setBounds(map1.getBounds());
            // });
        }

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
            return str.split(",");
        }

        // Adds a marker to the map.
        function addMarker(location, map, created_at, address, popis, kategoria, stav, typ_stavu_riesenia, id, popisRiesenia) {
            var marker = new google.maps.Marker({
                position: location,
                animation: google.maps.Animation.DROP,
                map: map,

            });


            if (popisRiesenia == null) {
                popisRiesenia = "Nepriradený"
            }

            var infowindow = new google.maps.InfoWindow({
                content: "<p>" + "<b>ID: </b>" + id + "</p>" +
                    "<p>" + "<b>Vytvorené dňa: </b>" + created_at + "</p>"
                    + "<p>" + "<b>Adresa: </b>" + address + "</p>"
                    + "<p>" + "<b>Popis: </b>" + popis + "</p>"
                    + "<p>" + "<b>Kategória: </b>" + kategoria + "</p>"
                    + "<p>" + "<b>Stav problému: </b>" + stav + "</p>"
                    + "<p>" + "<b>Stav riešenia problému: </b>" + typ_stavu_riesenia + "</p>"
                    + "<p>" + "<b>Popis stavu riešenia problému: </b>" + popisRiesenia + "</p>"
            });

            marker.addListener('click', function () {
                infowindow.open(map, marker);
            });
        }


        var markerString;
        var markersCount = 0;

        /**
         * Concatenates given lat and lng with an comma and returns it.
         * @param {!number} lat Latitude.
         * @param {!number} lng Longitude.
         * @return {string} Concatenated marker id.
         //  */
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

        // // Adds a marker to the map.
        function addMarker1(location, map, infowindow) {
            // Add the marker at the clicked location, and add the next-available label
            // from the array of alphabetical characters.
            var marker1 = new google.maps.Marker({
                position: location,
                animation: google.maps.Animation.DROP,
                map: map,
            });
            infowindow.open(map, marker1);
            bindMarkerEvents(marker1, infowindow); // bind right click event to marker
            markersCount++;
        }

        function bindMarkerEvents(marker, infoWindow) {
            google.maps.event.addListener(infoWindow,'closeclick',function(){
                markersCount--;
                removeMarker(marker); // remove it
            });
            google.maps.event.addListener(marker, "rightclick", function (point) {
                markersCount--;
                removeMarker(marker); // remove it
            });
        }

        function removeMarker(marker1) {
            marker1.setMap(null); // set markers setMap to null to remove it from map
            document.getElementById('poloha').value = "";
        }
    </script>

    <section>
        <div class="bnr-holder">
            <div class="bnr-title">
                <h1>Oči na ceste</h1>
            </div>
            <input id="pac-input" class="controls" type="text" placeholder="Vyhľadať">
            <div class="map" id="map"></div>
            <input type="checkbox" id="check-left">
            <label for="check-left">
                <i class="fas fa-question-circle" id="question-circle"></i>
                <i class="fas fa-times" id="cancel-btn-left"></i>
            </label>
            <input type="checkbox" id="check-right">
            <label for="check-right">
                <i class="fas fa-times" id="cancel-btn-right"></i>
                <i class="fas fa-chevron-circle-left" id="left-arrow"></i>
            </label>
            <div class="bnr-sidebar-left">
                <h1>O projekte</h1>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                    Contrary to popular belief, Lorem Ipsum is not simply random text.
                    It has roots in a piece of classical Latin literature from 45 BC, making it
                    over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia,
                    looked up one of the more obscure Latin words
                </p>
            </div>
            <div class="bnr-sidebar-right">
                <ul class="bnr-sidebar-options">
                    <!-- Authentication Links -->
                    @guest
                        <li class="main-nav-item">
                            <a class="main-nav-link" href="{{ route('login') }}">{{ __('Prihlásenie') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="main-nav-item">
                                <a class="main-nav-link" href="{{ route('register') }}">{{ __('Registrácia') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
                                    {{ __('Odhlásenie') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                    <li>
                        <a class="all-report-list" href="{{ route('welcomePage.allProblems') }}">Všetky hlásenia</a>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <section>
        <div id="create-form" class="create-form">
            <h1 class="create-form_header">Vytvorenie hlásenia</h1>
            @if ($errors->any())
                <div class="alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="start-form" action="{{ route('welcomePage.store') }}" method="POST">
                @csrf
                <label for="location_field">
{{--                    <span>Poloha <span class="required">*</span></span>--}}
                    <input id="poloha" class="form-input readonly" type="hidden" name="poloha" value="" readonly="true">
                </label>

                <label for="address_field"><span>Adresa <span class="required">*</span></span>
                    <input id="address" class="form-input readonly" type="text" name="address" value="" readonly="true">
                </label>

                <label for="category_field"><span>Kategória <span class="required">*</span></span>
                    <select id="kategoria" class="select-field"
                            name="kategoria_problemu_id">
                        @foreach($kategorie as $kategoria)
                            <option
                                value="{{ $kategoria->kategoria_problemu_id }}">
                                {{ $kategoria->nazov }}</option>
                        @endforeach
                    </select>
                </label>
                <label for="problemState_field"><span>Stav problému <span class="required">*</span></span>
                    <select id="stav_problemu" class="select-field"
                            name="stav_problemu_id">
                        @foreach($stavy as $stav)
                            <option
                                value="{{ $stav->stav_problemu_id }}">{{ $stav->nazov }}</option>
                        @endforeach
                    </select>
                </label>
                <label for="description_field"><span>Popis problému <span class="required">*</span></span><textarea id="popis_problemu" name="popis_problemu" class="textarea-field"></textarea></label>
                <div class="btn-form">
                    <label><span> </span><input type="submit" value="Submit" /></label>
                </div>
            </form>
        </div>
    </section>

@endsection
