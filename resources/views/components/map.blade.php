<script type="text/javascript">
    let map;
    let markersCount = 0;
    let infoWindow;

    function initAutocomplete() {
        const trnava = {lat: 48.3767994, lng: 17.5835082};
        map = new google.maps.Map(document.getElementById('map'), {
            center: trnava,
            zoom: 11,
            mapTypeId: 'roadmap'
        });
        infoWindow = new google.maps.InfoWindow();
        getCurrLocation(map, trnava);
        displayMarkers();

        var contentString = document.getElementById('create-form');
        var location = document.getElementById('poloha');
        // This event listener calls addMarker() when the map is clicked.
        google.maps.event.addListener(map, 'click', function (event) {
            if (markersCount < 1) {
                let lat = event.latLng.lat();
                let lng = event.latLng.lng();
                //old KEY
                // const KEY = "AIzaSyBwqdLzr2oJjx6qzdDY8YVE9m8-yRasOwc";
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

        var stavyRiesenia = {{$stavy_riesenia}};
        var popisyArr = {{$popisyArr}};

        let count = 0;
        var nazov_typu_riesenia;
        var popis_stavu_riesenia;

        @foreach($problems as $problem)
        var loc = split(" {{ $problem->poloha }}");

        @foreach($typy_stavov_riesenia as $typ)
        if (stavyRiesenia[count] === {{$typ->typ_stavu_riesenia_problemu_id}}) {
            nazov_typu_riesenia = "{{$typ->nazov}}";
        }
        @endforeach

            @foreach($popisyAll as $popis)
        if (popisyArr[count] === 0) {
            popis_stavu_riesenia = "Nepriradený popis";
        } else if (popisyArr[count] === "{{$popis->popis_stavu_riesenia_problemu_id}}") {
            popis_stavu_riesenia = "{{$popis->popis}}";
        }
        @endforeach

        if (popis_stavu_riesenia == null) {
            popis_stavu_riesenia = "Nepriradený popis";
        }

        markerCluster.addMarker(createMarker(getLocVar(loc[0], loc[1]), map, "{{ $problem->created_at}}",
            "{{ $problem->address }}", "{{ $problem->popis_problemu }}", "{{ $problem->KategoriaProblemu['nazov'] }}",
            "{{ $problem->StavProblemu['nazov'] }}", nazov_typu_riesenia, "{{$problem->problem_id}}", popis_stavu_riesenia));
        count++;
        @endforeach
    }

    function createMarker(location, map, created_at, address, popis, kategoria, stav, typ_stavu_riesenia, id, popisRiesenia) {
        let image;
        if (kategoria === 'Stav vozovky') {
            image = "https://i.imgur.com/KlEk7Rn.png";
        } else if (kategoria === 'Dopravné značenie') {
            image = "https://i.imgur.com/fuRl821.png";
        } else if (kategoria === 'Kvalita opravy') {
            image = "https://i.imgur.com/8AinVKN.png";
        } else if (kategoria === 'Zeleň') {
            image = "https://i.imgur.com/nUcHcHa.png";
        }
        //base marker
        else {
            image = "https://i.imgur.com/nHmUmuy.png";
        }

        let marker = new google.maps.Marker({
            position: location,
            animation: google.maps.Animation.DROP,
            map: map,
            description: "<p>" + "<b>ID: </b>" + id + "</p>"
                + "<p>" + "<b>Vytvorené dňa: </b>" + created_at + "</p>"
                + "<p>" + "<b>Adresa: </b>" + address + "</p>"
                + "<p>" + "<b>Popis: </b>" + popis + "</p>"
                + "<p>" + "<b>Kategória: </b>" + kategoria + "</p>"
                + "<p>" + "<b>Stav problému: </b>" + stav + "</p>"
                + "<p>" + "<b>Stav riešenia problému: </b>" + typ_stavu_riesenia + "</p>"
                + "<p>" + "<b>Popis stavu riešenia problému: </b>" + popisRiesenia + "</p>"
                + "<a href='#' data-target='#imageGallery' data-toggle='modal' onclick='showImage(" + id + ")'>Galéria obrázkov</a>",
            icon: image,
        });

        marker.addListener('click', function () {
            infoWindow.setOptions({
                content: marker.description,
            });
            infoWindow.open(map, marker);
        });

        return marker;
    }

    function showImage(id) {
        $.ajax({
            url: '/image/' + id,
            type: 'GET',
            success:function(data){
                let imageModal = $('#imageGallery');
                imageModal.find('.modal-content').html(data);
                imageModal.modal('show');
            },
            error: function () {
                let imageModal = $('#imageGallery');
                imageModal.html('Something went wrong!');
                imageModal.modal('show');
            }
        });
    }

    function split(str) {
        return str.split(",");
    }

    function getLocVar(lat, lng) {
        return new google.maps.LatLng(parseFloat(lat), parseFloat(lng));
    }

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

    setInterval(function () {
        $(".alert").fadeOut();
    }, 3000);
</script>

<div class="bnr-holder">
    <div id="map"></div>
</div>

<div class="modal fade" id="imageGallery" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        @include('partials.problemImage')
    </div>
</div>
