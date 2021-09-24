@extends('custom_layout.welcomePage.welcomePage_app')

@section('content')

    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif


    <section class="main-container h-100">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h1 class="text-center">Všetky hlásenia</h1>
                </div>


                <div class="col-12 d-flex justify-content-center flex-column">
                    <div class="table-responsive">
                        <table class="table main-table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Poloha</th>
                                <th scope="col">Vytvorené dňa</th>
                                <th scope="col">Kategória problému</th>
                                <th scope="col">Stav problému</th>
                                <th scope="col">Stav riešenia</th>
                                <th scope="col">Detail</th>

                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $counter = 1;
                            @endphp
                            @foreach($problems as $problem)

                                <tr>
                                    <td>{{ $counter }}</td>
                                    <td>{{ $problem->poloha }}
{{--                                        <script type="text/javascript">--}}
{{--                                            var KEY = "AIzaSyCkf657xylGn2KpKWraX_nLq0XHu6OghgQ"--}}
{{--                                            var url = `https://maps.googleapis.com/maps/api/geocode/json?latlng={{ $problem->poloha }}&key=${KEY}`;--}}
{{--                                            fetch(url)--}}
{{--                                                .then(response => response.json())--}}
{{--                                                .then(data => {--}}
{{--                                                    var location = data.results[0].formatted_address;--}}
{{--                                                    console.log(location);--}}
{{--                                                })--}}
{{--                                        </script>--}}
                                    </td>
                                    <td>{{ $problem->created_at }}</td>
                                    <td>{{ $problem->KategoriaProblemu['nazov'] }}</td>
                                    <td>{{ $problem->StavProblemu['nazov'] }}</td>
                                    @foreach($typy_stavov_riesenia as $typ)
                                        @if($stavy_riesenia[$counter-1] == $typ->typ_stavu_riesenia_problemu_id)
                                            <td>{{ $typ->nazov }}</td>
                                        @endif
                                    @endforeach

                                    <td><a href="/problem/{{ $problem->problem_id }}" class="c-black"><i
                                                class="fas fa-info"></i></a></td>
                                </tr>
                                @php
                                    $counter++;
                                @endphp
                            @endforeach


                            </tbody>
                        </table>
                    </div>
                    {{$problems->links()}}

                    @if(!empty(Session::get('success')))
                        <div class="alert alert-success"> {{ Session::get('success') }}</div>
                    @endif
                    @if(!empty(Session::get('error')))
                        <div class="alert alert-danger"> {{ Session::get('error') }}</div>
                    @endif

                </div>
            </div>
        </div>
    </section>

@endsection
