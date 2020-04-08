@extends('custom_layout.admin.admin_app')

@section('content')

    <div class="container">
        <div class="row">

            <div class="col-12 col-sm-12 col-md-6">

                <!-- tabulka s mojimi hlaseniami -->
                <ul class="detail update">
                    <li id="id"><p class="detail-text"><span>ID: </span>{{ $problem->problem_id }}</p></li>

                    <li id="poloha"><label class="update-label">Poloha:</label><input type="text" class="form-input"
                                                                                      value="{{ $problem->poloha }}">
                    </li>
                    <li id="popis"><label class="update-label">Popis:</label><input type="text" class="form-input"
                                                                                    value="{{ $problem->popis_problemu }}">
                    </li>
                    <li id="zadane"><p class="detail-text"><span>Zadane dňa: </span>{{ $problem->created_at }}</p></li>

                    <li id="priorita">
                        <label class="update-label">Priorita:</label>
                        <select id="priorita" class="form-select form-input" name="priorita">
                            @foreach($priority as $priorita)

                                @if($priorita->priorita_id == $problem->Priorita['priorita_id'])
                                    <option value="{{ $priorita->priorita_id }}"
                                            selected>{{ $priorita->priorita }}</option>

                                @else
                                    <option value="{{ $priorita->priorita_id }}">{{ $priorita->priorita }}</option>
                                @endif
                            @endforeach
                        </select>
                    </li>

                    <li id="kraj">
                        <label class="update-label">Kraj:</label>
                        <select id="kraj" class="form-select form-input" name="kraj">
                            @foreach($kraje as $kraj)

                                @if($kraj->kraj_id == $problem->Cesta->Kraj['kraj_id'])
                                    <option value="{{ $kraj->kraj_id }}"
                                            selected>{{ $kraj->nazov }}</option>

                                @else
                                    <option value="{{ $kraj->kraj_id }}">{{ $kraj->nazov }}</option>
                                @endif
                            @endforeach
                        </select>
                    </li>

                    <li id="katastralneUzemie">
                        <label class="update-label">Katastralne uzemie:</label>
                        <select id="katastralneUzemie" class="form-select form-input" name="katastralneUzemie">
                            @foreach($katastralne_uzemia as $uzemie)

                                @if($uzemie->katastralne_uzemie_id == $problem->Cesta->KatastralneUzemie['katastralne_uzemie_id'])
                                    <option value="{{ $uzemie->katastralne_uzemie_id }}"
                                            selected>{{ $uzemie->nazov }}</option>

                                @else
                                    <option value="{{ $uzemie->katastralne_uzemie_id }}">{{ $uzemie->nazov }}</option>
                                @endif
                            @endforeach
                        </select>
                    </li>


                    <li id="obec">
                        <label class="update-label">Obec:</label>
                        <select id="obec" class="form-select form-input" name="obec">
                            @foreach($obce as $obec)

                                @if($obec->obec_id == $problem->Cesta->Obec['obec_id'])
                                    <option value="{{ $obec->obec_id }}"
                                            selected>{{ $obec->nazov }}</option>

                                @else
                                    <option value="{{ $obec->obec_id }}">{{ $obec->nazov }}</option>
                                @endif
                            @endforeach
                        </select>
                    </li>

                    <li id="spravca">
                        <label class="update-label">Spravca komunikacie:</label>
                        <select id="spravca" class="form-select form-input" name="spravca">
                            @foreach($spravcovia as $spravca)

                                @if($spravca->spravca_id == $problem->Cesta->Spravca['spravca_id'])
                                    <option value="{{ $spravca->spravca_id }}"
                                            selected>{{ $spravca->nazov }}</option>

                                @else
                                    <option value="{{ $spravca->spravca_id }}">{{ $spravca->nazov }}</option>
                                @endif
                            @endforeach
                        </select>
                    </li>


                </ul>
            </div>
            <div class="col-12 col-sm-12 col-md-6">
                <ul class="detail update">

                    <!--  <li id="usek"><p class="detail-text"><span>Usek: </span>!!dorobit!!</p></li> -->
                    <li id="pouzivatel"><p class="detail-text"><span>Vytvorene: </span>{{ $problem->users['name'] }}</p>
                    </li>

                    <li id="stavRiesenia">
                        <label class="update-label">Stav riesenia problemu:</label>
                        <select id="stavRiesenia" class="form-select form-input" name="stavRiesenia">
                            @foreach($stavy_riesenia_problemu as $stav)

                                @if($stav->typ_stavu_riesenia_problemu_id == $problem->StavRieseniaProblemu['stav_riesenia_problemu_id'])
                                    <option value="{{ $stav->typ_stavu_riesenia_problemu_id }}"
                                            selected>{{ $stav->nazov }}</option>

                                @else
                                    <option value="{{ $stav->typ_stavu_riesenia_problemu_id }}">{{ $stav->nazov }}</option>
                                @endif
                            @endforeach

                        </select>
                    </li>

                    <li id="kategoria">
                        <label class="update-label">Kategória:</label>
                        <select id="kategoria" class="form-select form-input" name="kategoria_problemu_id">
                            <option value="1" selected>Stav vozovky</option>
                            <option value="2">Dopravné značenie</option>
                            <option value="3">Kvalita opravy</option>
                            <option value="4">Prerastená zeleň</option>
                        </select>

                    <li id="stavProblemu">
                        <label class="update-label">Stav problemu:</label>
                        <select id="stavProblemu" class="form-select form-input" name="stavProblemu">
                            <option value="1" selected>Chybajuca</option>
                            <option value="2">Poskodena</option>
                            <option value="3">Vyblednuta</option>
                            <option value="4">Zle viditelna</option>
                        </select>


                    <li id="priradene-vozidlo"><p class="detail-text">
                            <span>Priradene vozidlo: </span>{{$problem->PriradeneVozidlo->Vozidlo['SPZ'] }}</p></li>

                    <li id="priradenyZamestnanec"><p class="detail-text">
                            <span>Priradeny zamestnanec: </span>{{ $problem->PriradenyZamestnanec->users['name'] }}</p>
                    </li>


                    <li id="popisRieseniaProblemu">
                        <label class="update-label">Popis riesenia problemu</label>
                        <textarea id="popisRieseniaProblemu" rows="6" class="form-input"
                                  name="popis_problemu"></textarea>
                    </li>

                </ul>
            </div>
            <div class="col-12 mt-4">
                <!-- tabulka s mojimi hlaseniami -->
                <div class="d-flex align-items-center justify-content-center">
                    <button type="submit" class="btn btn-primary update-btn mr-3">Aktualizovať</button>
                    <button type="submit" class="btn btn-danger remove-btn">Zmazať</button>
                </div>
            </div>
        </div>
    </div>

@endsection
