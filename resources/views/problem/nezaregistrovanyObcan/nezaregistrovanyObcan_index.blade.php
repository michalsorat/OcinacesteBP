@extends('custom_layout.welcomePage.welcomePage_app')

@section('content')

    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif

    <section>
        <header class="main-head">
            <nav class="head-nav">
                <ul class="menu">
                    <li>
                        <a href={{ route('welcome') }}>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                                <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                            </svg>
                            <span>Mapa všetkých hlásení</span></a>
                    </li>
                    <li>
                        <a href={{ route('welcomePage.allProblems') }}>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-file-earmark-spreadsheet" viewBox="0 0 16 16">
                                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V9H3V2a1 1 0 0 1 1-1h5.5v2zM3 12v-2h2v2H3zm0 1h2v2H4a1 1 0 0 1-1-1v-1zm3 2v-2h3v2H6zm4 0v-2h3v1a1 1 0 0 1-1 1h-2zm3-3h-3v-2h3v2zm-7 0v-2h3v2H6z"/>
                            </svg>
                            <span>Zoznam všetkých hlásení</span></a>
                    </li>
                    <li>
                        <a href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                            </svg>
                            <span>O projekte</span></a>
                    </li>
                    <li>
                        <a href="{{ route('download') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-cloud-arrow-down-fill" viewBox="0 0 16 16">
                                <path d="M8 2a5.53 5.53 0 0 0-3.594 1.342c-.766.66-1.321 1.52-1.464 2.383C1.266 6.095 0 7.555 0 9.318 0 11.366 1.708 13 3.781 13h8.906C14.502 13 16 11.57 16 9.773c0-1.636-1.242-2.969-2.834-3.194C12.923 3.999 10.69 2 8 2zm2.354 6.854-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 1 1 .708-.708L7.5 9.293V5.5a.5.5 0 0 1 1 0v3.793l1.146-1.147a.5.5 0 0 1 .708.708z"/>
                            </svg>
                            <span>Stiahni mobilnú aplikáciu</span></a>
                    </li>
                    <li>
                        <a href={{ route('login') }}>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                            </svg>
                            <span>Prihlásenie</span></a>
                    </li>

                </ul>
            </nav>
        </header>
        <div>
            <h1 class="header_logo">Oči na ceste</h1>
        </div>
{{--        <section class="main-container h-100">--}}
{{--            <div class="container-fluid">--}}
{{--                <div class="row">--}}
{{--                    <div class="col-12">--}}
{{--                        <h1 class="header_logo">Oči na ceste</h1>--}}
{{--                    </div>--}}
{{--                    <div class="col-12 mt-5 mb-5">--}}
{{--                        <h1 class="text-center">Moje hlásenia</h1>--}}
{{--                    </div>--}}


{{--                    <div class="col-10 d-flex justify-content-center ml-5 flex-column">--}}
{{--                        <div class="table-responsive ml-5">--}}
{{--                            <table class="table main-table">--}}
{{--                                <thead>--}}
{{--                                <tr>--}}
{{--                                    <th scope="col">#</th>--}}
{{--                                    <th scope="col">Adresa</th>--}}
{{--                                    <th scope="col">Vytvorené dňa</th>--}}
{{--                                    <th scope="col">Kategória problému</th>--}}
{{--                                    <th scope="col">Stav problému</th>--}}
{{--                                    <th scope="col">Stav riešenia</th>--}}
{{--                                </tr>--}}
{{--                                </thead>--}}
{{--                                <tbody>--}}
{{--                                @php--}}
{{--                                    $counter = 1;--}}
{{--                                @endphp--}}
{{--                                @foreach($problems as $problem)--}}

{{--                                    <tr>--}}
{{--                                        <td>{{ $counter }}</td>--}}
{{--                                        <td>{{ $problem->address }}</td>--}}
{{--                                        <td>{{ $problem->created_at }}</td>--}}
{{--                                        <td>{{ $problem->KategoriaProblemu['nazov'] }}</td>--}}
{{--                                        <td>{{ $problem->StavProblemu['nazov'] }}</td>--}}
{{--                                        @foreach($typy_stavov_riesenia as $typ)--}}
{{--                                            @if($stavy_riesenia[$counter-1] == $typ->typ_stavu_riesenia_problemu_id)--}}
{{--                                                <td>{{ $typ->nazov }}</td>--}}
{{--                                            @endif--}}
{{--                                        @endforeach--}}
{{--                                    </tr>--}}
{{--                                    @php--}}
{{--                                        $counter++;--}}
{{--                                    @endphp--}}
{{--                                @endforeach--}}
{{--                                </tbody>--}}
{{--                            </table>--}}
{{--                        </div>--}}
{{--                        {{$problems->links()}}--}}

{{--                        @if(!empty(Session::get('success')))--}}
{{--                            <div class="alert alert-success"> {{ Session::get('success') }}</div>--}}
{{--                        @endif--}}
{{--                        @if(!empty(Session::get('error')))--}}
{{--                            <div class="alert alert-danger"> {{ Session::get('error') }}</div>--}}
{{--                        @endif--}}

{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </section>--}}
{{--    </section>--}}
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

@endsection
