@extends('layouts.app')

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
            addMarker(location, map);
        }

        function getLocVar(lat, lng) {
            return new google.maps.LatLng(parseFloat(lat), parseFloat(lng));
        }

        function split(str){
            return str.split(",");
        }

        // Adds a marker to the map.
        function addMarker(location, map) {
            let image;
            let category = "{{ $problem->KategoriaProblemu['nazov'] }}";
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
            });
        }

    </script>

    <section>
        <div class="container-fluid">
            <div class="row mt-xl-5 mt-md-4 mt-2">
                <div class="col-6">
                    <div id="map"></div>
                </div>
                <div class="col-6">
                    <ul class="detail">
                        <li><p class="detail-text"><span>Vytvorené dňa: </span>{{ $problem->created_at }}</p></li>
                        <li><p class="detail-text"><span>Poloha: </span>{{ $problem->poloha }}</p></li>
                        <li><p class="detail-text"><span>Adresa: </span>{{ $problem->address }}</p></li>
                        <li ><p class="detail-text"><span>Popis: </span>{{ $problem->popis_problemu }}</p></li>
                        <li><p class="detail-text">
                                <span>Kategória: </span>{{ $problem->KategoriaProblemu['nazov'] }}</p></li>
                        <li><p class="detail-text">
                                <span>Stav problému: </span>{{ $problem->StavProblemu['nazov'] }}</p></li>
                        <li><p class="detail-text">
                                <span>Stav riešenia problému: </span>{{ $stav_riesenia_problemu->TypStavuRieseniaProblemu['nazov'] }}
                            </p></li>
                        @if($popis_stavu_riesenia == null)
                            <li><p class="detail-text">
                                    <span>Popis stavu riešenia problému: </span>Popis zatiaľ nebol priradený
                                </p></li>
                        @else
                            <li><p class="detail-text">
                                    <span>Popis stavu riešenia problému: </span>{{$popis_stavu_riesenia->popis }}
                                </p></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection
