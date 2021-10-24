@extends('custom_layout.welcomePage.welcomePage_app')

@section('content')

    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif

    <header>
        <div class="d-flex justify-content-center h-auto">
            <img class="logo_img" src="{{ asset('img/logo02.png') }}">
            <h1 class="main_header">Oči na ceste</h1>
        </div>
        <nav class="navbar navbar-expand-md navbar-light bg-light">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('welcome') }}">Mapa hlásení</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ route('welcomePage.allProblems') }}">Zoznam všetkých hlásení <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('download') }}">Mobilná aplikácia</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">O projekte</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <section>
        <div class="filter"> <button class="btn btn-default" type="button" data-toggle="collapse" data-target="#mobile-filter" aria-expanded="false" aria-controls="mobile-filter">Filters<span class="fa fa-filter pl-1"></span></button>
        </div>
        <form action="{{ route('filter') }}"
              method="POST">
            @csrf
            <div class="row mr-1 ml-1" id="mobile-filter">
                <div class="col-sm-6">
                    <h6 class="p-1 border-bottom">Zadané</h6>
                    <select
                        id="orderBy" class="input-filter form-input w-100"
                        name="orderBy">
                        <option value="" selected disabled hidden>Vyberte možnosť</option>
                        <option value="1">Zoraď od najnovších</option>
                        <option value="2">Zoraď od najstarších</option>
                    </select>
                </div>
                <div class="col-sm-6">
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
                <div class="col-sm-6">
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
                <div class="col-sm-6">
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
                <div class="col-6 col-sm-9 d-flex justify-content-start">
                    <button type="button" class="btn btn-secondary my-3" data-toggle="modal" data-target="#exampleModal">
                        Zvoľte polohu
                    </button>
                </div>
                <div class="col-6 col-sm-3 d-flex justify-content-end">
                    <button id="search_btn" class="btn btn-primary m-3" type="submit">Filtruj</button>
                </div>
            </div>
        </form>
    </section>

    <section>
        <form action="{{ route('filter') }}"
              method="POST">
            @csrf
            <div id="sidebar">
                <div>
                    <h6 class="ml-4 border-bottom">Zadané</h6>
                    <select id="orderBy" class="input-filter form-input ml-3 w-75" name="orderBy">
                        <option value="" selected disabled hidden>Vyberte možnosť</option>
                        <option value="1">Zoraď od najnovších</option>
                        <option value="2">Zoraď od najstarších</option>
                    </select>
                </div>
                <div>
                    <h6 class="ml-4 mt-3 border-bottom">Kategória problému</h6>
                    <select id="kategoria_problemu_id" class="input-filter form-input ml-3 w-75" name="kategoria_problemu_id">
                        <option value="" selected disabled hidden>Vyberte možnosť</option>
                        @foreach($kategorie as $kategoria)
                            <option value="{{ $kategoria->kategoria_problemu_id }}"
                            >{{ $kategoria->nazov }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <h6 class="ml-4 mt-3 border-bottom">Stav problému</h6>
                    <select id="stav_problemu_id" class="input-filter form-input ml-3 w-75" name="stav_problemu_id">
                        <option value="" selected disabled hidden>Vyberte možnosť</option>
                        @foreach($stavyProblemu as $stav)
                            <option value="{{ $stav->stav_problemu_id }}">
                                {{ $stav->nazov}}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <h6 class="ml-4 mt-3 border-bottom">Stav riešenia problému</h6>
                    <select id="typ_stavu_riesenia_problemu" class="input-filter form-input ml-3 w-75" name="typ_stavu_riesenia_problemu_id">
                        <option value="" selected disabled hidden>Vyberte možnosť</option>
                        @foreach($typyStavovRieseniaProblemu as $stav)
                            <option value="{{ $stav->typ_stavu_riesenia_problemu_id}}"
                            >{{ $stav->nazov }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="button" class="btn btn-secondary ml-3 mt-3 w-80" data-toggle="modal" data-target="#locationModal">
                    Zvoľte polohu
                </button>
                <div>
                    <button id="filter_btn" class="btn btn-primary float-left" type="submit">Filtruj</button>
                </div>
            </div>
            <div class="modal fade" id="locationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            ...
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>

    <section>
        <table class="rwd-table">
            <thead>
            <tr>
                <th>#</th>
                <th>Adresa</th>
                <th>Vytvorené dňa</th>
                <th>Kategória problému</th>
                <th>Stav problému</th>
                <th>Stav riešenia</th>
            </tr>
            </thead>
            <tbody>
            @php
                $counter = 1;
            @endphp
            @foreach($problems as $problem)
                <tr>
                    <td data-th="#" id="hashtagID">{{ $counter }}</td>
                    <td data-th="Adresa">{{ $problem->address }}</td>
                    <td data-th="Vytvorené dňa">{{ $problem->created_at }}</td>
                    <td data-th="Kategória problému">{{ $problem->KategoriaProblemu['nazov'] }}</td>
                    <td data-th="Stav problému">{{ $problem->StavProblemu['nazov'] }}</td>
                    @foreach($typy_stavov_riesenia as $typ)
                        @if($stavy_riesenia[$counter-1] == $typ->typ_stavu_riesenia_problemu_id)
                            <td data-th="Stav riešenia">{{ $typ->nazov }}</td>
                        @endif
                    @endforeach
                </tr>
                @php
                    $counter++;
                @endphp
            @endforeach
            </tbody>
        </table>
    </section>

@endsection
