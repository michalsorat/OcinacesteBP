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
        <div class="container-fluid">
            <div class="row justify-content-end my-md-4 my-2">
                @if(Auth::user() != null && (Auth::user()->id == $problem->pouzivatel_id || Auth::user()->rola_id >= 3))
                    <button id="editBtn" class="btn btn-success mr-3" value="edit">Upraviť</button>
                    <button id="cancelBtn" class="btn btn-secondary mr-3">Zrušiť</button>
                    <form action="{{ route('problem.destroy', $problem->problem_id) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <button id="deleteBtn" class="btn btn-danger mr-3 mr-md-4">Zmazať</button>
                    </form>
                @endif
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
                            <div class="form-group col-sm-6 col-md-12 col-lg-6">
                                <label for="problemDesc">Popis problému:</label>
                                <textarea class="form-control" id="problemDesc" rows="3" name="problemDesc" disabled>{{ $problem->popis_problemu }}</textarea>
                            </div>
                            <div class="form-group col-sm-6 col-md-12 col-lg-6">
                                <label for="solutionDesc">Popis stavu riešenia problému:</label>
                                <textarea class="form-control align-content-start" id="solutionDesc" rows="3" readonly>@if($popis_stavu_riesenia == null)Popis riešenia zatiaľ nebol priradený@else{{$popis_stavu_riesenia->popis }}@endif</textarea>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="col-md-6 mt-4 mt-md-0">
                    <nav>
                        <div class="nav nav-tabs nav-fill" role="tablist">
                            <a class="nav-item nav-link active" data-toggle="tab" href="#problemHistory" role="tab" aria-controls="nav-problem-history" aria-selected="true">História riešenia problému</a>
                            <a class="nav-item nav-link" data-toggle="tab" href="#problemSolutionImages" role="tab" aria-controls="nav-problem-gallery-solution" aria-selected="false">Galéria riešenia problému</a>
                        </div>
                    </nav>
                    <div class="tab-content py-3 px-3 px-sm-0">
                        <div class="tab-pane fade show active" id="problemHistory" role="tabpanel" aria-labelledby="nav-home-tab">
                            <div id="history-table-holder">
                                <table class="table small table-hover table-bordered working-group-table">
                                    <thead>
                                    <tr class="text-center">
                                        <th class="align-middle" scope="col">Zmena vykonaná dňa</th>
                                        <th class="align-middle" scope="col">Typ</th>
                                        <th class="align-middle" scope="col">Popis</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($problem->problemHistory as $historyRecord)
                                        <tr class="text-center">
                                            <td>{{$historyRecord->created_at}}</td>
                                            <td>{{$historyRecord->type}}</td>
                                            <td>{{$historyRecord->description}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="problemSolutionImages" role="tabpanel" aria-labelledby="nav-profile-tab">
                            <div class="row" id="gallery">
                                @if($problem->problemSolImage->isEmpty())
                                    <h5 class="ml-5">Žiadne priradené obrázky riešenia problému</h5>
                                @endif
                                @foreach ($problem->problemSolImage as $key=>$image)
                                    <div class="col-12 col-sm-6 col-lg-3 mb-4">
                                        <div class="card">
                                            <div class="card-header mb-2">
                                                @if(Auth::user() != null && Auth::user()->rola_id >= 3)
                                                    <form action="{{ route('deleteSolImage', $image) }}" method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button class="btn deleteImgBtn" type="submit">
                                                            <i class="fa-solid fa-xmark"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                            <div class="card-body" data-toggle="modal" data-target="#imageSolModal">
                                                <img class="w-100" src="/storage/problemImages/{{ $image->nazov_suboru }}"
                                                     data-target="#carouselSolGallery" data-slide-to="{{$key++}}" alt="">
                                            </div>
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mt-4 mt-md-0">
                    <nav>
                        <div class="nav nav-tabs nav-fill" role="tablist">
                            <a class="nav-item nav-link active" data-toggle="tab" href="#problemImages" role="tab" aria-controls="nav-problem-gallery" aria-selected="true">Galéria problému</a>
                            @if($stav_riesenia_problemu->typ_stavu_riesenia_problemu_id != 4)
                                <a class="nav-item nav-link" data-toggle="tab" href="#newImageUploadTab" role="tab" aria-controls="nav-problem-solution" aria-selected="false">Nahrať nový obrázok</a>
                            @endif
                        </div>
                    </nav>
                    <div class="tab-content py-3 px-3 px-sm-0">
                        <div class="tab-pane fade show active" id="problemImages" role="tabpanel" aria-labelledby="nav-home-tab">
                            <div class="row" id="gallery">
                                @if($problem->problemImage->isEmpty())
                                    <h5 class="ml-5">Žiadne priradené obrázky problému</h5>
                                @endif
                                @foreach ($problem->problemImage as $key=>$image)
                                    <div class="col-12 col-sm-6 col-lg-3 mb-4">
                                        <div class="card">
                                            <div class="card-header mb-2">
                                                @if(Auth::user() != null && (Auth::user()->id == $problem->pouzivatel_id || Auth::user()->rola_id >= 3))
                                                    <form action="{{ route('deleteImage', $image) }}" method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button class="btn deleteImgBtn" type="submit">
                                                            <i class="fa-solid fa-xmark"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                            <div class="card-body" data-toggle="modal" data-target="#imageModal">
                                                <img class="w-100" src="/storage/problemImages/{{ $image->nazov_suboru }}"
                                                     data-target="#carouselGallery" data-slide-to="{{$key++}}" alt="">
                                            </div>
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="tab-pane fade" id="newImageUploadTab" role="tabpanel" aria-labelledby="nav-profile-tab">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <form method="POST" action="{{ route('problem.update', $problem->problem_id) }}" class="dropzone dz-clickable dropzone-holder" id="imageUploadDZ" enctype="multipart/form-data">
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
                            </div>
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

    <div class="modal fade" id="imageSolModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="carouselSolGallery" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($problem->problemSolImage as $image)
                                <div @if($loop->first) class="carousel-item active" @else class="carousel-item" @endif>
                                    <img class="d-block w-100" src="/storage/problemImages/{{$image->nazov_suboru}}" alt="problem-image">
                                </div>
                            @endforeach
                        </div>
                        <a class="carousel-control-prev" href="#carouselSolGallery" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselSolGallery" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        setInterval(function () {
            $(".alert").fadeOut();
        }, 3000);

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

        $(function() {
            Dropzone.autoDiscover = false;
            var myDropzone = new Dropzone("#imageUploadDZ");

            myDropzone.on("queuecomplete", function() {
                location.reload();
            });
        })

    </script>
@endsection
