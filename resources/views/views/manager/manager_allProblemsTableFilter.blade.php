@extends('layouts.app')

@section('content')

    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif

    <section>
        <div class="container-fluid">
            <div class="row mt-xl-5 mt-lg-4 mt-3">
                <div class="filter-holder col-xl-2 col-lg-3 col-12 px-0">
                    {{--                    <button--}}
                    {{--                        class="btn btn-default" id="expand-filter-btn" type="button" data-toggle="collapse" data-target="#filter" aria-expanded="false" aria-controls="filter">Filters<span class="fa fa-filter pl-1"></span>--}}
                    {{--                    </button>--}}
                    <form class="tableForm" action="{{ route('problem.index') }}" method="GET">
                        <div class="row mx-1" id="filter">
                            <div class="filter-option col-6 col-lg-12">
                                <h6 class="p-1 border-bottom">Zoradiť podľa</h6>
                                <select
                                    id="orderBy" class="input-filter form-input w-100"
                                    name="orderBy">
                                    <option value="" selected disabled hidden>Vyberte možnosť</option>
                                    <option value="1">Zoraď od najnovších</option>
                                    <option value="2">Zoraď od najstarších</option>
                                </select>
                            </div>
                            <div class="filter-option col-6 col-lg-12">
                                <h6 class="p-1 border-bottom">Kategória problému</h6>
                                <select
                                    id="kategoria_problemu_id" class="input-filter form-input w-100"
                                    name="kategoria_problemu_id">

                                    <option value="" selected disabled hidden>Vyberte možnosť</option>
                                    @foreach($kategorie as $kategoria)
                                        <option value="{{ $kategoria->kategoria_problemu_id }}"
                                        >{{ $kategoria->nazov }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="filter-option col-6 col-lg-12">
                                <h6 class="p-1 border-bottom">Stav problému</h6>
                                <select
                                    id="stav_problemu_id" class="input-filter form-input w-100"
                                    name="stav_problemu_id">
                                    <option value="" selected disabled hidden>Vyberte možnosť</option>
                                    @foreach($stavyProblemu as $stav)
                                        <option value="{{ $stav->stav_problemu_id }}">
                                            {{ $stav->nazov}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="filter-option col-6 col-lg-12">
                                <h6 class="p-1 border-bottom">Stav riešenia problému</h6>
                                <select
                                    id="typ_stavu_riesenia_problemu" class="input-filter form-input w-100"
                                    name="typ_stavu_riesenia_problemu_id">
                                    <option value="" selected disabled hidden>Vyberte možnosť</option>
                                    @foreach($typyStavovRieseniaProblemu as $stav)
                                        <option value="{{ $stav->typ_stavu_riesenia_problemu_id}}"
                                        >{{ $stav->nazov }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="filter-option col-6">
                                <h6 class="p-1 border-bottom">Lattitude</h6>
                                <input id="lattitude-input" class="form-input w-100" name="lattitude" value="" readonly>
                            </div>
                            <div class="filter-option col-6">
                                <h6 class="p-1 border-bottom">Longitude</h6>
                                <input id="longitude-input" class="form-input w-100" name="longitude" value="" readonly>
                            </div>
                            <div>
                                <input id="radius-dist" class="form-input w-100" name="radius" value="" readonly type="hidden">
                            </div>
                            <div class="col-6 my-3">
                                <button type="button" class="btn btn-secondary w-100" data-toggle="modal" data-target="#locationModal">
                                    Zvoľte polohu
                                </button>
                            </div>
                            <div class=" col-6 my-3 d-lg-flex justify-content-end">
                                <button id="filterBtn" class="btn btn-primary w-100" type="submit">Filtruj</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="tableHolder col-xl-10 col-lg-9 col-12">
                    @include('components.citizen.citizenProblemsTable')
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="locationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-3" id="exampleModalLabel">Zvoľte polohu podľa ktorej chcete filtrovať problémy spadajúce do daného okruhu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="modalMap"></div>
                    <div>
                        <h6 class="mt-3 mb-2 border-bottom">Zvoľte veľkosť okruhu pre vyfiltrovanie:</h6>
                        <input id="radiusRange" type="range" min="1" max="50" value="5" style="width: 300px;">
                        <p>Vzdialenosť v km: <span id="radiusValue"></span></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="clear-filter-radius" type="button" class="btn btn-secondary" data-dismiss="modal">Vymaž filter</button>
                    <button id="save-filter-radius" type="button" class="btn btn-primary" data-dismiss="modal">Ulož filter</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        let map, marker, circle;
        function initAutocomplete() {
            const trnavaLatLon = {lat: 48.3767994, lng: 17.5835082};

            map = new google.maps.Map(document.getElementById('modalMap'), {
                center: trnavaLatLon,
                zoom: 11,
                mapTypeId: 'roadmap'
            });
            marker = new google.maps.Marker({
                map: map,
                position: trnavaLatLon,
                draggable: true,
            });
            circle = new google.maps.Circle({
                map: map,
                radius: 5000,    // metres
                fillColor: '#157526'
            });
            circle.bindTo('center', marker, 'position');
        }
        $('#locationModalBtn').click(function (){
            initAutocomplete();
        })
        let slider = document.getElementById("radiusRange");
        let radValue = document.getElementById("radiusValue");
        radValue.innerHTML = slider.value;

        slider.oninput = function (){
            radValue.innerHTML = this.value;
            circle.setRadius(parseInt(this.value)*1000);
        }

        $('#save-filter-radius').click(function (){
            document.getElementById("lattitude-input").value = marker.getPosition().lat();
            document.getElementById("longitude-input").value = marker.getPosition().lng();
            document.getElementById("radius-dist").value = slider.value;
        })
        $('#clear-filter-radius').click(function (){
            document.getElementById("lattitude-input").value = "";
            document.getElementById("longitude-input").value = "";
            document.getElementById("radius-dist").value = "";
        })

        var tableForm = $(".tableForm");
        tableForm.submit(function(e){
            e.preventDefault();
            var formData = tableForm.serialize();

            $.ajax({
                url:'/problem',
                type:'GET',
                data:formData,
                success:function(data){
                    $('.tableHolder').html(data);
                },
                error: function () {
                    $('.tableHolder').html('Something went wrong');
                }
            });
        });

    </script>

@endsection

