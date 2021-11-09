@extends('custom_layout.welcomePage.welcomePage_app')

@section('content')
    <script type="text/javascript">
        var map;

        function initAutocomplete() {
            const trnava = {lat: 48.3767994, lng: 17.5835082};
            map = new google.maps.Map(document.getElementById('map'), {
                center: trnava,
                zoom: 11,
                mapTypeId: 'roadmap'
            });
            getCurrLocation(map, trnava);
            displayMarkers();

            var contentString = document.getElementById('create-form');
            var location = document.getElementById('poloha');
            // This event listener calls addMarker() when the map is clicked.
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
                            for (let i = 0; i < 2; i++) {
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

                    addMarker(event.latLng, map, infowindow);
                    location.value = getMarkerString(lat, lng);
                } else window.alert("Môžete vytvoriť iba jeden problém súčasne. " +
                    "Pre odstránenie označenia z mapy, kliknite pravým tlačidlom myše na označené miesto." +
                    " Následne môžete vytvoriť nové označenie.");

            })
        }

        function getMarkerString(lat, lng) {
            return lat.toFixed(6) + ',' + lng.toFixed(6);
        }

        function getCurrLocation(map, defaultLocation) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                        };
                        map.setCenter(pos);
                    },
                    () => {
                        // could not fetch location
                        map.setCenter(defaultLocation);
                    }
                );
            } else {
                map.setCenter(defaultLocation);
            }
        }

        function displayMarkers() {
            let markerCluster = new MarkerClusterer(map, [], {
                imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'
            });

            var JSARR = <?php echo json_encode($stavy_riesenia); ?>;
            var popisyArr = <?php echo json_encode($popisyArr); ?>;

            let poc = 0;
            var nazov_typu_riesenia;
            var popis_stavu_riesenia;

            @foreach($problems as $problem)

                var loc = split(" {{ $problem->poloha }}");

                @foreach($typy_stavov_riesenia as $typ)
                    if (JSARR[poc] === {{$typ->typ_stavu_riesenia_problemu_id}}) {
                        nazov_typu_riesenia = "{{$typ->nazov}}";
                    }
                @endforeach

                @foreach($popisyAll as $popis)
                    if (popisyArr[poc] === 0) {
                        popis_stavu_riesenia = "Nepriradený popis";
                    }
                    else if (popisyArr[poc] === "{{$popis->popis_stavu_riesenia_problemu_id}}") {
                        popis_stavu_riesenia = "{{$popis->popis}}";
                    }
                @endforeach

                if (popis_stavu_riesenia == null) {
                    popis_stavu_riesenia = "Nepriradený popis";
                }
                markerCluster.addMarker(createMarker(getLocVar(loc[0], loc[1]), map, "{{ $problem->created_at}}",
                    "{{ $problem->address }}", "{{ $problem->popis_problemu }}", "{{ $problem->KategoriaProblemu['nazov'] }}",
                    "{{ $problem->StavProblemu['nazov'] }}", nazov_typu_riesenia, "{{$problem->problem_id}}", popis_stavu_riesenia));
                poc++;

            @endforeach
        }

        // function createMarker(latlng, name, address1, address2, address3, address4, image, wwwsite) {
        //
        //     var image = "https://i.imgur.com/8AinVKN.png";
        //     var marker = new google.maps.Marker({
        //         map: map,
        //         position: latlng,
        //         title: name,
        //         icon: image,
        //         // icon: image - so shows default icon in code snippet
        //     });
        //
        //     google.maps.event.addListener(marker, 'click', function() {
        //
        //         var iwContent = '<div id="iw_container">' +
        //             '<div class="iw_title">' + name + '</div>' +
        //             '<div class="iw_content">' + address1 + '<br />' +
        //             address2 + '<br />' + address3 + '<br />' + address4 + '<br />' +
        //             wwwsite + '</div></div>';
        //
        //
        //         infoWindow.setContent(iwContent);
        //
        //
        //         infoWindow.open(map, marker);
        //     });
        //     return marker;
        // }
        function createMarker(location, map, created_at, address, popis, kategoria, stav, typ_stavu_riesenia, id, popisRiesenia) {
            var image;
            if (kategoria === 'Stav vozovky'){
                image = "https://i.imgur.com/KlEk7Rn.png";
            }
            else if (kategoria === 'Dopravné značenie'){
                image = "https://i.imgur.com/fuRl821.png";
            }
            else if (kategoria === 'Kvalita opravy'){
                image = "https://i.imgur.com/8AinVKN.png";
            }
            else if (kategoria === 'Zeleň'){
                image = "https://i.imgur.com/nUcHcHa.png";
            }
            //base marker
            else {
                image = "https://i.imgur.com/nHmUmuy.png";
            }

            var marker = new google.maps.Marker({
                position: location,
                animation: google.maps.Animation.DROP,
                map: map,
                icon: image,
            });

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

            return marker;
        }

        function split(str) {
            return str.split(",");
        }

        function getLocVar(lat, lng) {
            return new google.maps.LatLng(parseFloat(lat), parseFloat(lng));
        }

        var markersCount = 0;

        // // Adds a marker to the map.
        function addMarker(location, map, infowindow) {
            let marker = new google.maps.Marker({
                position: location,
                animation: google.maps.Animation.DROP,
                map: map,
            });
            infowindow.open(map, marker);
            bindMarkerEvents(marker, infowindow);
            markersCount++;
        }

        function bindMarkerEvents(marker, infoWindow) {
            google.maps.event.addListener(infoWindow, 'closeclick', function () {
                markersCount--;
                removeMarker(marker); // remove it
            });
            google.maps.event.addListener(marker, "rightclick", function (point) {
                markersCount--;
                removeMarker(marker); // remove it
            });
        }

        function removeMarker(marker) {
            marker.setMap(null); // set markers setMap to null to remove it from map
        }

        function findProblemWithAddress(address) {
            @foreach($problems as $problem)
            if ("{{ $problem->address}}" === address) {
                let latLonArr = split(" {{ $problem->poloha }}");
                smoothZoom(map, 16, map.getZoom());
                map.setCenter(getLocVar(latLonArr[0], latLonArr[1]));
            }
            @endforeach
        }

        function smoothZoom(map, max, cnt) {
            let z;
            if (cnt < max) {
                z = google.maps.event.addListener(map, 'zoom_changed', function (event) {
                    google.maps.event.removeListener(z);
                    smoothZoom(map, max, cnt + 1);
                });
                setTimeout(function () {
                    map.setZoom(cnt)
                }, 40);
            }
        }
    </script>

    <header>
        <nav class="navbar navbar-expand-xl navbar-dark bg-dark">
            <h1 class="main_header">Oči na ceste</h1>
            <form class="form-inline">
                <div class="input-group d-none d-sm-flex mx-auto">
                    <input id="search_input" class="form-control" type="search"
                           placeholder="Vyhľadaj hlásenie podľa adresy" autocomplete="off" size="30">
                    <span class="input-group-append">
                        <button id="search_btn" class="btn btn-outline-success" type="button">
                        <i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>
            </form>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto mr-3">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ route('welcome') }}">Mapa<span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('welcomePage.allProblems') }}">Zoznam hlásení</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('download') }}">Mobilná aplikácia</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">O projekte</a>
                    </li>
                    <form class="form-inline d-sm-none">
                        <div class="input-group">
                            <input id="search_input" class="form-control" type="search"
                                   placeholder="Vyhľadaj hlásenie podľa adresy" autocomplete="off" size="30">
                            <span class="input-group-append">
                                <button id="search_btn" class="btn btn-outline-success" type="button">
                                <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                </ul>
            </div>
        </nav>
        <script type="text/javascript">
            var path = "{{ route('autocomplete') }}";
            $('#search_input').typeahead({
                source: function (query, process) {
                    return $.get(path, {query: query}, function (data) {
                        return process(data);
                    });
                }
            });
            const search_button = document.getElementById("search_btn");

            search_button.addEventListener("click", function () {
                findProblemWithAddress(document.getElementById('search_input').value);
            });
        </script>
    </header>

    <section>
        <div class="bnr-holder">
            <div id="map"></div>
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
                    <input id="poloha" class="input-field" type="hidden" name="poloha" value="" readonly="true">
                </label>

                <label for="address_field"><span>Adresa <span class="required">*</span></span>
                    <input id="address" class="input-field" type="text" name="address" value="" readonly="true">
                </label>

                <label for="category_field"><span>Kategória <span class="required">*</span></span>
                    <select id="kategoria" class="input-field"
                            name="kategoria_problemu_id">
                        @foreach($kategorie as $kategoria)
                            <option
                                value="{{ $kategoria->kategoria_problemu_id }}">
                                {{ $kategoria->nazov }}</option>
                        @endforeach
                    </select>
                </label>
                <label for="problemState_field"><span>Stav problému <span class="required">*</span></span>
                    <select id="stav_problemu" class="input-field"
                            name="stav_problemu_id">
                        @foreach($stavy as $stav)
                            <option
                                value="{{ $stav->stav_problemu_id }}">{{ $stav->nazov }}</option>
                        @endforeach
                    </select>
                </label>
                <label for="description_field"><span>Popis problému <span class="required">*</span></span>
                    <textarea
                        id="popis_problemu" name="popis_problemu" class="textarea-field">
                    </textarea>
                </label>
                <div class="form-group">
                    <input type="file" class="form-control-file" id="exampleFormControlFile1">
                    <small id="emailHelp" class="form-text text-muted">Odfotťe problém a vložte na toto miesto</small>
                </div>
                <div class="btn-form">
                    <label><input type="submit" value="Submit"/></label>
                </div>
            </form>
        </div>
    </section>

    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAFM1--RiO7MvE1qixa1jYWpWkau9YcJRg&libraries=places&callback=initAutocomplete">
    </script>

@endsection
