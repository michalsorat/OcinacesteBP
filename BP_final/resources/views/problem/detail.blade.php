@extends('custom_layout.admin_app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-sm-12 col-md-6 col-lg-7">
            <div id="map"></div>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-5">

            <!-- tabulka s mojimi hlaseniami -->
            <ul class="detail">
                <li id="id"><p class="detail-text"><span>ID: </span>{{ $problem->problem_id }}</p></li>
                <li id="poloha"><p class="detail-text"><span>Poloha: </span>{{ $problem->poloha }}</p></li>
                <li id="popis"><p class="detail-text"><span>Popis: </span>{{ $problem->popis_problemu }}</p></li>
                <li id="zadane"><p class="detail-text"><span>Zadane dňa: </span>{{ $problem->created_at }}</p></li>
                <li id="priorita"><p class="detail-text"><span>Priorita: </span>{{ $problem->Priorita['priorita'] }}</p></li>
                <li id="kraj"><p class="detail-text"><span>Kraj: </span>{{ $problem->Cesta->Kraj['nazov'] }}</p></li>
                <li id="katastralneUzemie"><p class="detail-text"><span>Katastralne uzemie: </span>{{ $problem->Cesta->KatastralneUzemie['nazov'] }}</p></li>
                <li id="obec"><p class="detail-text"><span>Obec: </span>{{ $problem->Cesta->Obec['nazov'] }}</p></li>
                <li id="spravca"><p class="detail-text"><span>Spravca komunikacie: </span>{{ $problem->Cesta->Spravca['nazov'] }}</p></li>
                <li id="usek"><p class="detail-text"><span>Usek: </span>!!dorobit!!</p></li>
                <li id="pouzivatel"><p class="detail-text"><span>Vytvorene: </span>{{ $problem->users['name'] }}</p></li>
                <li id="stav-riesenia"><p class="detail-text"><span>Stav riešenia problemu: </span>{{ $problem->StavRieseniaProblemu->TypStavuRieseniaProblemu['nazov'] }}</p></li>
                <li id="kategoria"><p class="detail-text"><span>Kategória: </span>{{ $problem->KategoriaProblemu['nazov'] }}</p></li>
                <li id="stav-problemu"><p class="detail-text"><span>Stav problému: </span>{{ $problem->StavProblemu['nazov'] }}</p></li>
                <li id="priradeny-zamestnanec"><p class="detail-text"><span>Priradeny zamestnanec: </span>{{ $problem->PriradenyZamestnanec->users['name'] }}</p></li>
                <li id="priradene-vozidlo"><p class="detail-text"><span>Priradene vozidlo: </span>{{$problem->PriradeneVozidlo->Vozidlo['SPZ'] }}</p></li>
                <li id="popis-stavu-riesenia-problemu"><p class="detail-text"><span>Popi stavu riesenia problemu: </span>{{$problem->PopisStavuRieseniaProblemu['popis'] }}</p></li>
            </ul>
            <!-- tabulka s mojimi hlaseniami -->
        </div>
    </div>
</div>

@endsection
