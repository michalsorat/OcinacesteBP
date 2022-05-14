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
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{ session('status') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>{{ session('error') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <div class="container-fluid">
            <div class="row justify-content-end my-md-4 my-2">
                <button id="solveProblemBtn" class="btn btn-info mr-3">Odoslať na schválenie</button>
                <button id="editBtn" class="btn btn-success mr-3" value="edit">Upraviť</button>
                <button id="cancelBtn" class="btn btn-secondary mr-3">Zrušiť</button>
                <form action="{{ route('problem.destroy', $problem->problem_id) }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <button id="deleteBtn" class="btn btn-danger mr-3 mr-md-4">Zmazať</button>
                </form>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="map-detail" id="map"></div>
                </div>
                <div class="col-md-6 mt-4 mt-md-0">
                    <form id="problemDetails" action="{{ route('problem.update', $problem->problem_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-row">
                            <div class="form-group col-sm-4">
                                <label for="problemId">Problém ID:</label>
                                <input type="text" class="form-control" id="problemId" value="{{ $problem->problem_id }}" readonly>
                            </div>
                            <div class="form-group col-sm-8">
                                <label for="createdAt">Vytvorené dňa:</label>
                                <input type="text" class="form-control" id="createdAt" value="{{ $problem->created_at }}" readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-sm-6">
                                <label for="location">Poloha:</label>
                                <input type="text" class="form-control" id="location" value="{{ $problem->poloha }}" readonly>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="address">Adresa:</label>
                                <input type="text" class="form-control" id="address" value="{{ $problem->address }}" readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-sm-6 col-xl-4">
                                <label for="problemCategory">Kategória problému:</label>
                                <select id="problemCategory" class="form-control" name="newCategoryId" disabled>
                                    <option value="{{ $problem->kategoria_problemu_id }}" selected>{{ $problem->KategoriaProblemu['nazov'] }}</option>
                                    @foreach($categories as $category)
                                        @if($category->kategoria_problemu_id != $problem->KategoriaProblemu['kategoria_problemu_id'])
                                            <option value="{{ $category->kategoria_problemu_id }}">{{ $category->nazov }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-6 col-xl-4">
                                <label for="problemState">Stav problému:</label>
                                <select id="problemState" class="form-control" name="newStateId" disabled>
                                    <option value="{{ $problem->stav_problemu_id }}" selected>{{ $problem->StavProblemu['nazov'] }}</option>
                                    @foreach($problemStates as $problemState)
                                        @if($problemState->stav_problemu_id != $problem->StavProblemu['stav_problemu_id'])
                                            <option value="{{ $problemState->stav_problemu_id }}">{{ $problemState->nazov }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-6 col-xl-4">
                                <label for="problemSolutionState">Stav riešenia problému:</label>
                                <input type="text" class="form-control" id="problemSolutionState" value="{{ $stav_riesenia_problemu->TypStavuRieseniaProblemu['nazov'] }}" readonly>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="problemDesc">Popis problému:</label>
                                <textarea class="form-control" id="problemDesc" rows="3" name="problemDesc" disabled>{{ $problem->popis_problemu }}</textarea>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-md-6 mt-4 mt-md-0">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('solveProblem', $problem->problem_id) }}" class="dropzone dz-clickable dropzone-holder" id="imageUploadDZ" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div>
                                    <h4 class="text-center">KLIKNUTÍM ZVOĽTE OBRÁZKY NA NAHRATIE</h4>
                                </div>
                                <div class="dz-default dz-message">
                                    <div class="mb-2">
                                        <i class="fa-solid fa-cloud-arrow-up"></i>
                                    </div>
                                    <span>Sem presuňte obrázky na nahratie</span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-6 mt-4 mt-md-0">
                    <form method="POST" action="{{ route('solveProblem', $problem->problem_id) }}" id="solveProblemForm">
                        @csrf
                        @method('PUT')
                        <label for="solutionDesc">Popis stavu riešenia problému:</label>
                        <textarea class="form-control align-content-start" id="solutionDesc" rows="8" name="solutionDesc" placeholder="Tu popíšte postupy riešenia problému"></textarea>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        setInterval(function () {
            $(".alert").fadeOut();
        }, 3000);

        $('#solveProblemBtn').on('click', function () {
            $('#solveProblemForm').submit();
        })

        $('#editBtn').on('click', function () {
            if ($(this).val() === 'edit') {
                $('#problemDetails :input').prop("disabled", false);
                $(this).val('save');
                $(this).html('Uložiť');
                $('#cancelBtn').show();
            }
            else if ($(this).val() === 'save') {
                $('#problemDetails').submit();
            }
        });
        $('#cancelBtn').on('click', function () {
            $('#problemDetails :input').prop("disabled", true);
            let editBtn = $('#editBtn');
            editBtn.html('Upraviť');
            editBtn.val('edit');
            $(this).hide();
        })

        // $(function() {
        //     Dropzone.autoDiscover = false;
        //     var myDropzone = new Dropzone("#imageUploadDZ");
        //
        //     myDropzone.on("queuecomplete", function() {
        //         location.reload();
        //     });
        // })

    </script>
@endsection
