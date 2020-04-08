@extends('custom_layout.obcan.obcan_app')

@section('content')

    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif

    <div id="update-modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Aktualizácia záznamu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form class="start-form" action="{{ route('problem.store') }}" method="post">

                        <!-- vytvara skryte vstupne pole, ktore zabranuje utoku cross site scripting -->
                        @csrf
                        <div class="w-100 mb-2">
                            <label for="poloha">Poloha *</label>
                            <input id="poloha" class="form-input" type="text" name="poloha">
                        </div>

                        <div class="w-100 mb-2">
                            <label for="kategoria">Kategória *</label>
                            <select id="kategoria" class="form-select form-input" name="kategoria_problemu_id">
                                <option value="1">Stav vozovky</option>
                                <option value="2">Dopravné značenie</option>
                                <option value="3">Kvalita opravy</option>
                                <option value="4">Prerastená zeleň</option>
                            </select>
                        </div>

                        <div class="w-100 mb-2">
                            <label for="stav_problemu">Stav problému *</label>
                            <select id="stav_problemu" class="form-select form-input" name="stav_problemu_id">
                                <option value="1">Chýbajúca</option>
                                <option value="2">Poškodená</option>
                                <option value="3">Vyblednutá</option>
                                <option value="4">Zle viditeľná</option>
                            </select>
                        </div>


                        <div class="w-100 mb-2">
                            <label for="popis_problemu">Popis problému *</label>
                            <textarea id="popis_problemu" rows="6" class="form-input" name="popis_problemu"></textarea>
                        </div>

                        <div class="w-100 mb-2">
                            <label for="fotka">Vložiť fotku</label>
                            <input id="fotka" type="file">
                        </div>
                        <button type="submit" class="btn btn-primary" name="submit">Aktualizovať</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
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
                        <li><button type="button" class="btn btn-danger delete">Vymazať</button></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <section class="main-container h-100">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="text-center">Moje hlásenia</h1>
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
                            <th scope="col">Aktualizuj záznam</th>
                            <th scope="col">Vymaž záznam</th>
                            <th scope="col">Detail</th>
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
                                <td><button type="button" data-toggle="modal" data-target="#update-modal" class="border-0"><i class="fas fa-edit"></i></button></td>
                                <td><button type="button" data-toggle="modal" data-target="#delete-modal" class="border-0"><i class="far fa-trash-alt"></i></button></td>
                                <td><a href="#" class="c-black"><i class="fas fa-info"></i></a></td>

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
