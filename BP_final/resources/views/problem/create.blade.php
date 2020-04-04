@extends('custom_layout.app')

@section('content')
    <section class="main-container h-100">
        <div class="container-fluid h-100">
            <div class="row h-100">

                <!-- mapa -->
                <div class="col-12 col-sm-12 col-md-6 col-lg-7">
                    <div id="map"></div>
                </div>
                <!-- mapa -->

                <div class="col-12 col-sm-12 col-md-6 col-lg-5">
                    <div class="container">
                        <div class="row">
                            <!-- nadpis -->
                            <div class="col-12">
                                <h1 class="text-center">Vytvorenie hlásenia</h1>
                            </div>
                            <!-- nadpis -->


                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="col-12 d-flex justify-content-center p-0">

                                <!-- formular -->
                                <!-- <form class="start-form"> -->
                                <form class="start-form" action="{{ route('problem.store') }}" method="post">

                                    <!-- vytvara skryte vstupne pole, ktore zabranuje utoku cross site scripting -->
                                    @csrf
                                    <div class="w-100 mb-2">
                                        <label for="poloha"> Poloha *</label>
                                        <input id="poloha" class="form-input" type="text" name="poloha" value="">
                                    </div>

                                    <div class="w-100 mb-2">
                                        <label for="kategoria">Kategória *</label>
                                        <select id="kategoria" class="form-select form-input"
                                                name="kategoria_problemu_id">
                                            <option value="1">Stav vozovky</option>
                                            <option value="2">Dopravné značenie</option>
                                            <option value="3">Kvalita opravy</option>
                                            <option value="4">Prerastená zeleň</option>
                                        </select>
                                    </div>

                                    <div class="w-100 mb-2">
                                        <label for="stav_problemu">Stav problému *</label>
                                        <select id="stav_problemu" class="form-select form-input"
                                                name="stav_problemu_id">
                                            <option value="1">Chýbajúca</option>
                                            <option value="2">Poškodená</option>
                                            <option value="3">Vyblednutá</option>
                                            <option value="4">Zle viditeľná</option>
                                        </select>
                                    </div>


                                    <div class="w-100 mb-2">
                                        <label for="popis_problemu">Popis problému *</label>
                                        <textarea id="popis_problemu" rows="6" class="form-input"
                                                  name="popis_problemu"></textarea>
                                    </div>

                                    <div class="w-100 mb-2">
                                        <label for="fotka">Vložiť fotku</label>
                                        <input id="fotka" type="file">
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="submit">Vytvorit</button>
                                </form>
                                <!-- formular -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
