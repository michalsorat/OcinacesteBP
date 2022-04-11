@extends('layouts.app')

@section('content')
    <section>
        <div class="filter-option">
            <h6 class="p-1 border-bottom">Kategorie</h6>
            @foreach($kategorie as $kategoria)
                <div class="form-check ml-3">
                    <input class="role-check form-check-input categoryCB" type="checkbox" value="{{$kategoria->kategoria_problemu_id}}" id="checkbox{{$kategoria->kategoria_problemu_id}}" name="checkedCategories[]" checked>
                    <label class="form-check-label" for="checkbox{{$kategoria->kategoria_problemu_id}}">
                        {{$kategoria->nazov}}
                    </label>
                </div>
            @endforeach
        </div>
        <div class="filter-option">
            <h6 class="p-1 border-bottom">Zobrazit vytlky</h6>
                <div class="form-check ml-3">
                    <input class="role-check form-check-input" type="checkbox" value="1" id="showBumpsCB" name="showBumps" checked>
                    <label class="form-check-label" for="showBumpsCB">
                        Zobraziť výltky
                    </label>
                </div>
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

    <section>
        <div id="create-form" class="create-form">
            <h1 id="test" class="create-form_header">Vytvorenie hlásenia</h1>
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
                    <textarea
                        id="popis_problemu" name="popis_problemu" class="textarea-field">
                        </textarea>
                </label>
                <div class="form-group">
                    <input type="file" class="form-control-file active-upload" name="uploaded_images[]" multiple>
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
        $('input[type="checkbox"]').on('change', function() {
            console.log('haha');
            let checkboxArr = [];
            let isBump = 0;
            $('.categoryCB[type="checkbox"]:checked').each(function() {
                checkboxArr.push($(this).val());
            });
            let showBumpsCB = $('#showBumpsCB');
            if (showBumpsCB.is(':checked')) {
                isBump = showBumpsCB.val();
            }
            $.ajax({
                url: '/',
                type:'GET',
                data:{isBump: isBump, checkedCategories: checkboxArr},
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
