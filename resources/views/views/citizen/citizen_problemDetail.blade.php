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
                    <div class="map-detail" id="map"></div>
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
                <div class="col-6">
                    <nav>
                        <div class="nav nav-tabs nav-fill" role="tablist">
                            <a class="nav-item nav-link active" data-toggle="tab" href="#nav-problem" role="tab" aria-controls="nav-problem" aria-selected="true">Galéria problému</a>
                            <a class="nav-item nav-link" data-toggle="tab" href="#nav-problem-solution" role="tab" aria-controls="nav-problem-solution" aria-selected="false">Galéria riešenia problému</a>
                        </div>
                    </nav>
                    <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-problem" role="tabpanel" aria-labelledby="nav-home-tab">
                            <div class="row" id="gallery" data-toggle="modal" data-target="#imageModal">
                                @foreach ($problem->problemImage as $key=>$image)
                                    <div class="col-12 col-sm-6 col-lg-3">
                                        <img class="w-100" src="/storage/problemImages/{{ $image->nazov_suboru }}"
                                             data-target="#carouselGallery" data-slide-to="{{$key++}}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-problem-solution" role="tabpanel" aria-labelledby="nav-profile-tab">
                            Et et consectetur ipsum labore excepteur est proident excepteur ad velit occaecat qui minim occaecat veniam. Fugiat veniam incididunt anim aliqua enim pariatur veniam sunt est aute sit dolor anim. Velit non irure adipisicing aliqua ullamco irure incididunt irure non esse consectetur nostrud minim non minim occaecat. Amet duis do nisi duis veniam non est eiusmod tempor incididunt tempor dolor ipsum in qui sit. Exercitation mollit sit culpa nisi culpa non adipisicing reprehenderit do dolore. Duis reprehenderit occaecat anim ullamco ad duis occaecat ex.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="carouselGallery" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($problem->problemImage as $image)
                                <div @if($loop->first) class="carousel-item active" @else class="carousel-item" @endif>
                                    <img class="d-block w-100" src="/storage/problemImages/{{$image->nazov_suboru}}" alt="problem-image">
                                </div>
                            @endforeach
                        </div>
                        <a class="carousel-control-prev" href="#carouselGallery" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselGallery" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
