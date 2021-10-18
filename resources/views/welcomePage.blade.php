@extends('custom_layout.welcomePage.welcomePage_app')

@section('content')

    <script type="text/javascript">

        var map;
        function initAutocomplete() {
            var trnava = {lat: 48.3767994, lng: 17.5835082};

            //all problems
            map = new google.maps.Map(document.getElementById('map'), {
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

        var geocoder;
        function codeAddress(address) {
            geocoder = new google.maps.Geocoder();
            geocoder.geocode({
                'address': address
            }, function(results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    smoothZoom(map, 16, map.getZoom());
                    map.setCenter(results[0].geometry.location);
                }
            });
        }
        function smoothZoom (map, max, cnt) {
            if (cnt < max) {
                z = google.maps.event.addListener(map, 'zoom_changed', function(event){
                    google.maps.event.removeListener(z);
                    smoothZoom(map, max, cnt + 1);
                });
                setTimeout(function(){map.setZoom(cnt)}, 40); // 80ms is what I found to work well on my system -- it might not work well on all systems
            }
        }
    </script>

    <section>
        <div class="bnr-holder">
            <div class="d-flex justify-content-center h-auto">
                <img class="logo_img" src="{{ asset('img/logo02.png') }}">
                <h1 class="main_header">Oči na ceste</h1>
            </div>
            <nav class="navbar navbar-expand-xl navbar-light bg-light">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="{{ route('welcome') }}">Mapa hlásení <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('welcomePage.allProblems') }}">Zoznam všetkých hlásení</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('download') }}">Mobilná aplikácia</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">O projekte</a>
                        </li>
    {{--                    <li class="nav-item">--}}
    {{--                        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>--}}
    {{--                    </li>--}}
                    </ul>
                    <form class="form-inline my-2 my-lg-0">
                        <input id="search" class="form-control mr-sm-2" size="30" type="search" placeholder="Vyhľadaj hlásenie podľa adresy" aria-label="Search">
                        <button id="search_btn" class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                    </form>
                </div>
            </nav>
            <script type="text/javascript">
                var path = "{{ route('autocomplete') }}";
                $('#search').typeahead({
                    source:  function (query, process) {
                        return $.get(path, { query: query }, function (data) {
                            return process(data);
                        });
                    }
                });

                const search_button = document.getElementById("search_btn");
                search_button.addEventListener("click", function (){
                    codeAddress(document.getElementById('search').value);
                });
            </script>
            <input id="pac-input" class="controls" type="text" placeholder="Vyhľadať">
            <div class="map" id="map"></div>
{{--            <header class="main-head">--}}
{{--                <nav class="head-nav">--}}
{{--                    <ul class="menu">--}}
{{--                        <li>--}}
{{--                            <a href={{ route('welcome') }}>--}}
{{--                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">--}}
{{--                                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>--}}
{{--                                </svg>--}}
{{--                            <span>Mapa všetkých hlásení</span></a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href={{ route('welcomePage.allProblems') }}>--}}
{{--                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-file-earmark-spreadsheet" viewBox="0 0 16 16">--}}
{{--                                    <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V9H3V2a1 1 0 0 1 1-1h5.5v2zM3 12v-2h2v2H3zm0 1h2v2H4a1 1 0 0 1-1-1v-1zm3 2v-2h3v2H6zm4 0v-2h3v1a1 1 0 0 1-1 1h-2zm3-3h-3v-2h3v2zm-7 0v-2h3v2H6z"/>--}}
{{--                                </svg>--}}
{{--                                <span>Zoznam všetkých hlásení</span></a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="#">--}}
{{--                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">--}}
{{--                                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>--}}
{{--                                </svg>--}}
{{--                                <span>O projekte</span></a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('download') }}">--}}
{{--                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-cloud-arrow-down-fill" viewBox="0 0 16 16">--}}
{{--                                    <path d="M8 2a5.53 5.53 0 0 0-3.594 1.342c-.766.66-1.321 1.52-1.464 2.383C1.266 6.095 0 7.555 0 9.318 0 11.366 1.708 13 3.781 13h8.906C14.502 13 16 11.57 16 9.773c0-1.636-1.242-2.969-2.834-3.194C12.923 3.999 10.69 2 8 2zm2.354 6.854-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 1 1 .708-.708L7.5 9.293V5.5a.5.5 0 0 1 1 0v3.793l1.146-1.147a.5.5 0 0 1 .708.708z"/>--}}
{{--                                </svg>--}}
{{--                                <span>Stiahni mobilnú aplikáciu</span></a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href={{ route('login') }}>--}}
{{--                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">--}}
{{--                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>--}}
{{--                                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>--}}
{{--                                </svg>--}}
{{--                                <span>Prihlásenie</span></a>--}}
{{--                        </li>--}}

{{--                    </ul>--}}
{{--                </nav>--}}
{{--            </header>--}}

{{--            <div class="bnr-sidebar-right">--}}
{{--                <ul class="bnr-sidebar-options">--}}
{{--                    <li>--}}
{{--                        <a class="all-report-map" href="{{ route('welcome') }}">Mapa všetkých problémov</a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a class="all-report-list" href="{{ route('welcomePage.allProblems') }}">Zoznam všetkých hlásení</a>--}}
{{--                    </li>--}}
{{--                    @guest--}}
{{--                        <li class="main-nav-item">--}}
{{--                            <a class="main-nav-link" href="{{ route('login') }}">{{ __('Prihlásenie') }}</a>--}}
{{--                        </li>--}}
{{--                        @if (Route::has('register'))--}}
{{--                            <li class="main-nav-item">--}}
{{--                                <a class="main-nav-link" href="{{ route('register') }}">{{ __('Registrácia') }}</a>--}}
{{--                            </li>--}}
{{--                        @endif--}}
{{--                    @else--}}
{{--                        <li class="nav-item dropdown">--}}
{{--                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>--}}
{{--                                {{ Auth::user()->name }} <span class="caret"></span>--}}
{{--                            </a>--}}
{{--                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">--}}
{{--                                <a class="dropdown-item" href="{{ route('logout') }}"--}}
{{--                                   onclick="event.preventDefault();--}}
{{--                           document.getElementById('logout-form').submit();">--}}
{{--                                    {{ __('Odhlásenie') }}--}}
{{--                                </a>--}}
{{--                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">--}}
{{--                                    @csrf--}}
{{--                                </form>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                    @endguest--}}
{{--                </ul>--}}
{{--            </div>--}}
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
