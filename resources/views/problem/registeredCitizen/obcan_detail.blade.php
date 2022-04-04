@extends('custom_layout.obcan.obcan_app')

@section('content')

    <script type="text/javascript">

        function initAutocomplete() {

            var loc = split(" {{ $problem->poloha }}");
            var location = getLocVar(loc[0], loc[1]);

            var map = new google.maps.Map(document.getElementById('map'), {
                center: location,
                zoom: 11,
                mapTypeId: 'roadmap'
            });
            addMarker(location, map, "{{ $problem->poloha }}");
            console.log("{{ $problem->KategoriaProblemu['nazov'] }}");
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

        function split(str){
            return str.split(",");
        }

        // Adds a marker to the map.
        function addMarker(location, map, poloha) {
            let image;
            let category = "{{ $problem->KategoriaProblemu['nazov'] }}";
            console.log("{{ $problem->KategoriaProblemu['nazov'] }}");
            if (category === 'Stav vozovky') {
                image = "https://i.imgur.com/KlEk7Rn.png";
            } else if (category === 'Dopravné značenie') {
                image = "https://i.imgur.com/fuRl821.png";
            } else if (category === 'Kvalita opravy') {
                image = "https://i.imgur.com/8AinVKN.png";
            } else if (category === 'Zeleň') {
                image = "https://i.imgur.com/nUcHcHa.png";
            }
            //base marker
            else {
                image = "https://i.imgur.com/nHmUmuy.png";
            }
            new google.maps.Marker({
                position: location,
                animation: google.maps.Animation.DROP,
                map: map,
                icon: image,
                title: "Poloha: " + poloha
            });
        }

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

