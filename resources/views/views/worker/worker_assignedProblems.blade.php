@extends('layouts.app')

@section('content')
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session('status') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="container-fluid">
        <div class="row mt-xl-5 mt-md-4 mt-2 mx-1">
            <table class="rwd-table group-problems-table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Adresa</th>
                    <th>Kategória problému</th>
                    <th>Stav problému</th>
                    <th>Stav riešenia</th>
                    <th>Priorita</th>
                    <th>Priradené dňa</th>
{{--                    <th>Vyriešiť</th>--}}
                </tr>
                </thead>
                <tbody>
                @if($workingGroup == null)
                    <tr>
                        <td colspan="7" class="text-center fa-2x">Nie ste priradený do žiadnej pracovnej čaty!</td>
                    </tr>
                @elseif($assignedProblems == null)
                    <tr>
                        <td colspan="7" class="text-center">Žiadne pridelené problémy pracovnej čate</td>
                    </tr>
                @else
                    @foreach($assignedProblems as $counter=>$problem)
                        <tr onclick="window.location='{{ route('problemDetail', $problem->problem_id) }}'">
                            <td data-th="#" id="hashtagID">{{ ++$counter }}</td>
                            <td data-th="Adresa">{{ $problem->address }}</td>
                            <td data-th="Kategória problému">{{ $problem->KategoriaProblemu['nazov'] }}</td>
                            <td data-th="Stav problému">{{ $problem->StavProblemu->nazov }}</td>
                            @foreach($solStatusTypes as $type)
                                @if($solStatusAssignedProblems[$counter-1] == $type->typ_stavu_riesenia_problemu_id)
                                    <td data-th="Stav riešenia">{{ $type->nazov }}</td>
                                @endif
                            @endforeach
                            <td data-th="Priorita">
                                {{$priorities[$problem->priorita_id - 1]->priorita}}
                            </td>
                            <td data-th="Priradené dňa">
                                {{$problem->created_at}}
                            </td>
                            <td class="d-none d-md-block">
                                <i class="fa-solid fa-angles-right"></i>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>

@endsection
