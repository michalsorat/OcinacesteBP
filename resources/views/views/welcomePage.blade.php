@extends('layouts.app')

@section('content')

    <section>
        <div class="filter-menu">
            @foreach($kategorie as $kategoria)
                <div class="form-check">
                    <input class="role-check form-check-input categoryCB" type="checkbox" value="{{$kategoria->kategoria_problemu_id}}" id="checkbox{{$kategoria->kategoria_problemu_id}}" name="checkedCategories[]" checked>
                    <label class="form-check-label" for="checkbox{{$kategoria->kategoria_problemu_id}}">
                        {{$kategoria->nazov}}
                    </label>
                    @if($kategoria->kategoria_problemu_id == 1)
                        <img src="https://i.imgur.com/KlEk7Rn.png" alt="Stav vozovky">
                    @elseif($kategoria->kategoria_problemu_id == 2)
                        <img src="https://i.imgur.com/fuRl821.png" alt="Dopravné značenie">
                    @elseif($kategoria->kategoria_problemu_id == 3)
                        <img src="https://i.imgur.com/8AinVKN.png" alt="Kvalita opravy">
                    @elseif($kategoria->kategoria_problemu_id == 4)
                        <img src="https://i.imgur.com/nUcHcHa.png" alt="Zeleň">
                    @endif
                </div>
            @endforeach
            <div class="form-check">
                <input class="role-check form-check-input" type="checkbox" value="0" id="showBumpsCB" name="showBumps" checked>
                <label class="form-check-label" for="showBumpsCB">
                    Autom. detekované výtlky
                </label>
            </div>
        </div>

        <button class="filter-btn btn" type="button">
            <i class="fa-solid fa-sort"></i>
        </button>

        <div class="slider-holder">
            <div class="row">
                <label class="col-1" for="dateFrom">Od:</label>
                <input class="col-5 col-md-3 mb-3" type="text" id="dateFrom" style="border: 0; font-weight: bold;" readonly/>
                <input type="hidden" id="dateFromMysql" name="dateFrom">
                <div class="d-none d-md-block col-md-4"></div>
                <label class="col-1" for="dateTo">Do:</label>
                <input class="col-5 col-md-3 mb-3" type="text" id="dateTo" style="border: 0;font-weight: bold;" readonly/>
                <input type="hidden" id="dateToMysql" name="dateTo">
            </div>
            <div id="slider-range"></div>
        </div>

    </section>

    <section id="map-section">
        @include('components.map')
    </section>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session('status') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                @foreach ($errors->all() as $error)
                    <strong>{{ $error }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                @endforeach
        </div>
    @endif

    <section>
        <div id="create-form" class="create-form">
            <h1 id="test" class="create-form_header">Vytvorenie hlásenia</h1>

            <form class="start-form" action="{{ route('createProblem') }}" method="POST"
                  enctype="multipart/form-data">
                @csrf
                <label for="location_field">
                    <input id="poloha" class="input-field" type="hidden" name="poloha" value="">
                </label>

                <label for="address_field"><span>Adresa <span class="required">*</span></span>
                    <input id="address" class="input-field" type="text" name="address" value="" readonly>
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
                    <textarea id="popis_problemu" name="popis_problemu" class="textarea-field"></textarea>
                </label>
                <div class="form-group">
                    <input type="file" class="form-control-file" id="uploadedFiles" name="uploaded_images[]" multiple>
                    <button class="btn" id="cancelUploadBtn" type="button">
                        <i class="fa-solid fa-xmark"></i>
                    </button>

                    <small id="imageUploadHint" class="form-text text-muted">Odfotťe problém a vložte obrázok na toto
                        miesto</small>
                </div>
                <div class="btn-form">
                    <label><input type="submit" value="Submit"/></label>
                </div>
            </form>
        </div>
    </section>

    <script>
        setInterval(function () {
            $(".alert").fadeOut();
        }, 3000);

        $('#uploadedFiles').on('change', function () {
            if ($(this).val()) {
                $('#cancelUploadBtn').show();
            }
            else {
                $('#cancelUploadBtn').hide();
            }
        });

        $('#cancelUploadBtn').on('click', function () {
            $('#uploadedFiles').val('');
            $(this).hide();
        })

        $('input[type="checkbox"]').on('change', function() {
            let checkboxArr = [];
            let isBump;
            $('.categoryCB[type="checkbox"]:checked').each(function() {
                checkboxArr.push($(this).val());
            });
            let showBumpsCB = $('#showBumpsCB');
            if (showBumpsCB.is(':checked')) {
                isBump = 'showAll';
            }
            else isBump = showBumpsCB.val();

            let dateFrom = $("#dateFromMysql").val();
            let dateTo = $("#dateToMysql").val();

            $.ajax({
                url: '/',
                type:'GET',
                data:{isBump: isBump, checkedCategories: checkboxArr, dateFrom: dateFrom, dateTo: dateTo},
                success:function(data){
                    $('#map-section').html(data);
                    initAutocomplete();
                },
                error: function () {
                    $('#map-section').html('Something went wrong');
                }
            });
        });

        $('.filter-btn').on('click', function () {
            let alreadyClicked = $('.clicked').length;
            let alreadyClickedSlider = $('.clicked-slider').length;

            if (alreadyClicked && alreadyClickedSlider) {
                $('.filter-menu').removeClass('clicked');
                $('.slider-holder').removeClass('clicked-slider');
            }
            else {
                $('.filter-menu').addClass('clicked');
                $('.slider-holder').addClass('clicked-slider');

            }
        })

        $(function () {
            let minDate = '{{$problems[0]->created_at}}';
            let minDateTimeParts= minDate.split(/[- :]/);
            minDateTimeParts[1]--;
            let minDateObject = new Date(...minDateTimeParts);
            let sliderRange = $('#slider-range');
            let dateFrom, dateTo;

            sliderRange.slider({
                range: true,
                min: minDateObject.getTime() / 1000,
                max: new Date().getTime() / 1000,
                step: 5000,
                values: [minDateObject.getTime() / 1000, new Date().getTime() / 1000],
                slide: function (event, ui) {
                    $("#dateFrom").val(new Date(ui.values[0] * 1000).toLocaleDateString());
                    $("#dateTo").val(new Date(ui.values[1] * 1000).toLocaleDateString());
                }
            });
            dateFrom = new Date(sliderRange.slider("values", 0) * 1000);
            dateTo = new Date(sliderRange.slider("values", 1) * 1000);
            $("#dateFrom").val(dateFrom.toLocaleDateString());
            $("#dateFromMysql").val(dateFrom.toISOString().slice(0, 19).replace('T', ' '));
            $("#dateTo").val(dateTo.toLocaleDateString());
            $("#dateToMysql").val(dateTo.toISOString().slice(0, 19).replace('T', ' '));
        });

        $("#slider-range").on("slidestop", function(event, ui) {
            let checkboxArr = [];
            let isBump;
            $('.categoryCB[type="checkbox"]:checked').each(function() {
                checkboxArr.push($(this).val());
            });
            let showBumpsCB = $('#showBumpsCB');
            if (showBumpsCB.is(':checked')) {
                isBump = 'showAll';
            }
            else isBump = showBumpsCB.val();

            let dateFromInput = $("#dateFromMysql");
            let dateToInput = $("#dateToMysql");
            dateFromInput.val(new Date(ui.values[0] * 1000).toISOString().slice(0, 19).replace('T', ' '));
            dateToInput.val(new Date(ui.values[1] * 1000).toISOString().slice(0, 19).replace('T', ' '));

            let dateFrom = dateFromInput.val();
            let dateTo = dateToInput.val();
            $.ajax({
                url: '/',
                type:'GET',
                data:{isBump: isBump, checkedCategories: checkboxArr, dateFrom: dateFrom, dateTo: dateTo},
                success:function(data){
                    $('#map-section').html(data);
                    initAutocomplete();
                },
                error: function () {
                    $('#map-section').html('Something went wrong');
                }
            });
        });
    </script>
@endsection
