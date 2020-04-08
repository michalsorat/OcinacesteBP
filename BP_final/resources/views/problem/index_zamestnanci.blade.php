@extends('custom_layout.admin.admin_app')

@section('content')

    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif

    <div id="delete-modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Vymazať záznam</h5>
                </div>
                <div class="modal-body">

                    <p>Ste si istý, že chcete vymazať záznam?</p>
                    <ul class="d-flex align-items-center justify-content-center mt-4">
                        <li><button type="button" class="btn btn-primary cancel mr-4" data-dismiss="modal" aria-label="Close">Zrušiť</button></li>
                        <!--<li><button type="button" class="btn btn-danger delete">Vymazať</button></li>-->
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <section class="main-container h-100">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h1 class="text-center">Všetky hlásenia</h1>
                </div>
                <div class="col-12 d-flex justify-content-center flex-column">
                    <table class="table main-table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Poloha</th>
                            <th scope="col">Zadané</th>
                            <th scope="col">Kategória</th>
                            <th scope="col">Stav problému</th>
                            <th scope="col">Stav riešenia</th>
                            <th scope="col">Priorita</th>
                            <th scope="col">Pouzivatel</th>
                            <th scope="col">Zamestnanec</th>
                            <th scope="col">Vozidlo</th>
                            <th scope="col">Edit</th>
                            <th scope="col">Detail</th>
                            <th scope="col">Vymaž záznam</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($problems as $problem)

                            <tr>
                                <td>{{ $problem->problem_id }}</td>
                                <td>{{ $problem->poloha }}</td>
                                <td>{{ $problem->created_at }}</td>
                                <td>{{ $problem->KategoriaProblemu['nazov'] }}</td>
                                <td>{{ $problem->StavProblemu['nazov'] }}</td>
                                <td>{{ $problem->StavRieseniaProblemu->TypStavuRieseniaProblemu['nazov'] }}</td>
                                <td>{{ $problem->Priorita['priorita'] }}</td>
                                <td>{{ $problem->users['name'] }}</td>
                                <td>{{ $problem->PriradenyZamestnanec->users['name'] }}</td>
                                <td>{{ $problem->PriradeneVozidlo->Vozidlo['SPZ'] }}</td>

                                <td><a href="/problem/{{ $problem->problem_id }}/edit" class="c-black"><i class="fas fa-edit"></i></a></td>
                                <td><a href="/problem/{{ $problem->problem_id }}" class="c-black"><i class="fas fa-info"></i></a></td>
                                <td>
                                    <form action="{{ route('problem.destroy', $problem->problem_id) }}" method="POST">
                                        @method('DELETE')
                                        @csrf
                                        <p class="text-center">
                                            <button type="submit" class="border-0"><i class="far fa-trash-alt"></i></button>
                                        </p>
                                    </form>
                                </td>
                                <!--<td><button type="button" data-toggle="modal" data-target="#update-modal" class="border-0"><i class="fas fa-edit"></i></button></td> -->
                                <!-- <td><a href="/problem/{{ $problem->problem_id }}/delete" class="border-0"><i class="far fa-trash-alt"></i></a></td> -->
                                <!-- <td><button type="button" data-toggle="modal" data-target="#delete-modal" class="border-0"><i class="far fa-trash-alt"></i></button></td> -->
                            </tr>
                        @endforeach

                        </tbody>

                    </table>
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
