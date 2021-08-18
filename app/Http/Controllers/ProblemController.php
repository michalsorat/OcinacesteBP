<?php

namespace App\Http\Controllers;

use App\Models\PopisStavuRieseniaProblemu;
use App\Models\PriradeneVozidlo;
use App\Models\PriradenyZamestnanec;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Models\Problem;
use App\Models\KategoriaProblemu;
use App\Models\Komentar;
use App\Models\StavProblemu;
use App\Models\Priorita;
use App\Models\Kraj;
use App\Models\KatastralneUzemie;
use App\Models\Obec;
use App\Models\Vozidlo;
use App\Models\Spravca;
use App\Models\TypStavuRieseniaProblemu;
use App\Models\StavRieseniaProblemu;
use App\Models\FotkaProblemu;
use App\Models\FotkaStavuRieseniaProblemu;
use Illuminate\Support\Facades\Auth;
use App\User;


class ProblemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     * nacitanie problemov
     * nacitanie posledneho zaznamu daneho problemu z tabulky stavy_riesenia_problemov,
     * kde sa nachadza aktualny stav riesenia pre dany problem
     * (ostatne stavy pred poslednym su historicke stavy riesenia)
     * get atribut typ_stavu_riesenia_problemu_id
     * vlozenie do pola stavy_riesenia
     */

    public function index(Request $request)
    {
        $rola = Auth::user()->rola_id;
        $user_id = Auth::user()->id;
        $typy_stavov = TypStavuRieseniaProblemu::all();



        if (($rola == 1) || ($rola == 2)) {

            $problems = Problem::where('pouzivatel_id', '=', $user_id)->simplePaginate(10);
            $stavy_riesenia = array();
            foreach ($problems as $problem) {
                $typ = DB::table('stav_riesenia_problemu')
                    ->where('problem_id', '=', $problem->problem_id)
                    ->latest('stav_riesenia_problemu_id')->first();
                array_push($stavy_riesenia, $typ->typ_stavu_riesenia_problemu_id);
            }

            return view('problem.obcan.obcan_index')
                ->with('problems', $problems)
                ->with('stavy_riesenia', $stavy_riesenia)
                ->with('typy_stavov_riesenia', $typy_stavov);
        } else {


            $problems = Problem::simplePaginate(10);
            $users = User::all();
            $vsetky_vozidla = Vozidlo::all();
            $stavy_riesenia = array();
            $priradeni_zamestnanci = array();
            $priradene_vozidla = array();

            $kategorie = KategoriaProblemu::all();
            $stavyProblemu = StavProblemu::all();
            $typySTavovRieseniaProblemu = TypStavuRieseniaProblemu::all();
            $priority = Priorita::all();
            $zamestnanciAll = User::where('rola_id', '>', '2')->get();


            foreach ($problems as $problem) {
                $typ = DB::table('stav_riesenia_problemu')
                    ->where('problem_id', '=', $problem->problem_id)
                    ->latest('stav_riesenia_problemu_id')->first();
                array_push($stavy_riesenia, $typ->typ_stavu_riesenia_problemu_id);

                $zamestnanci = DB::table('priradeny_zamestnanec')
                    ->where('problem_id', '=', $problem->problem_id)
                    ->latest('priradeny_zamestnanec_id')->first();
                if ($zamestnanci != null)
                    array_push($priradeni_zamestnanci, $zamestnanci->zamestnanec_id);
                else
                    array_push($priradeni_zamestnanci, 0);

                $vozidla = DB::table('priradene_vozidlo')
                    ->where('problem_id', '=', $problem->problem_id)
                    ->latest('priradene_vozidlo_id')->first();


                if ($vozidla != null)
                    array_push($priradene_vozidla, $vozidla->vozidlo_id);
                else
                    array_push($priradene_vozidla, 0);
            }


            if ($rola == 3)
                return view('problem.admin.admin_index')
                    ->with('problems', $problems)
                    ->with('stavy_riesenia', $stavy_riesenia)
                    ->with('typy_stavov_riesenia', $typy_stavov)
                    ->with('priradeni_zamestnanci', $priradeni_zamestnanci)
                    ->with('zamestnanci', $users)
                    ->with('priradene_vozidla', $priradene_vozidla)
                    ->with('vozidla', $vsetky_vozidla)
                    ->with('kategorie', $kategorie)
                    ->with('stavyProblemu', $stavyProblemu)
                    ->with('typyStavovRieseniaProblemu', $typySTavovRieseniaProblemu)
                    ->with('priority', $priority)
                    ->with('VsetciZamestnanci', $zamestnanciAll);
            if ($rola == 4)
                return view('problem.dispecer.dispecer_index')
                    ->with('problems', $problems)
                    ->with('stavy_riesenia', $stavy_riesenia)
                    ->with('typy_stavov_riesenia', $typy_stavov)
                    ->with('priradeni_zamestnanci', $priradeni_zamestnanci)
                    ->with('zamestnanci', $users)
                    ->with('priradene_vozidla', $priradene_vozidla)
                    ->with('vozidla', $vsetky_vozidla)
                    ->with('kategorie', $kategorie)
                    ->with('stavyProblemu', $stavyProblemu)
                    ->with('typyStavovRieseniaProblemu', $typySTavovRieseniaProblemu)
                    ->with('priority', $priority)
                    ->with('VsetciZamestnanci', $zamestnanciAll);

            if ($rola == 5)
                return view('problem.manazer.manazer_index')
                    ->with('problems', $problems)
                    ->with('stavy_riesenia', $stavy_riesenia)
                    ->with('typy_stavov_riesenia', $typy_stavov)
                    ->with('priradeni_zamestnanci', $priradeni_zamestnanci)
                    ->with('zamestnanci', $users)
                    ->with('priradene_vozidla', $priradene_vozidla)
                    ->with('vozidla', $vsetky_vozidla)
                    ->with('kategorie', $kategorie)
                    ->with('stavyProblemu', $stavyProblemu)
                    ->with('typyStavovRieseniaProblemu', $typySTavovRieseniaProblemu)
                    ->with('priority', $priority)
                    ->with('VsetciZamestnanci', $zamestnanciAll);
        }
    }


    public function filter(Request $request)
    {

        if($request->orderBy == "2" ){
            $final = Problem::orderBy('created_at', 'DESC')->get();
        }
        else{
            $final = Problem::orderBy('created_at', 'ASC')->get();
        }

        if($request->kategoria_problemu_id != null){
            foreach($final as $problem){
                $id = $problem->problem_id;
                if($problem->kategoria_problemu_id != $request->kategoria_problemu_id){

                    $key = $final->search(function ($item) use ($id) {
                        return $item->problem_id == $id;
                    });

                    $final->pull($key);
                }
            }
        }

        if($request->stav_problemu_id != null){
            foreach($final as $problem){
                $id = $problem->problem_id;
                if($problem->stav_problemu_id != $request->stav_problemu_id){

                    $key = $final->search(function ($item) use ($id) {
                        return $item->problem_id == $id;
                    });

                    $final->pull($key);
                }
            }
        }

        if($request->priorita_id != null){
            foreach($final as $problem){
                $id = $problem->problem_id;
                if($problem->priorita_id != $request->priorita_id){

                    $key = $final->search(function ($item) use ($id) {
                        return $item->problem_id == $id;
                    });

                    $final->pull($key);
                }
            }
        }


        if($request->pouzivatel_id != null){
            foreach($final as $problem){
                $id = $problem->problem_id;
                if($problem->pouzivatel_id != $request->pouzivatel_id){

                    $key = $final->search(function ($item) use ($id) {
                        return $item->problem_id == $id;
                    });

                    $final->pull($key);
                }
            }
        }

        if ($request->typ_stavu_riesenia_problemu_id != null) {
            foreach ($final as $problem) {

                $id = $problem->problem_id;

                $stav_riesenia = DB::table('stav_riesenia_problemu')
                    ->where('problem_id', '=', $problem->problem_id)
                    ->latest('stav_riesenia_problemu_id')->first();

                if ($request->typ_stavu_riesenia_problemu_id !=
                    $stav_riesenia->typ_stavu_riesenia_problemu_id) {

                    $key = $final->search(function ($item) use ($id) {
                        return $item->problem_id == $id;
                    });

                    $final->pull($key);
                }
            }
        }

        if ($request->vozidlo_id != null) {
            foreach ($final as $problem) {

                $id = $problem->problem_id;

                $vozidlo = DB::table('priradene_vozidlo')
                    ->where('problem_id', '=', $problem->problem_id)
                    ->latest('priradene_vozidlo_id')->first();


                if ($vozidlo != null) {
                    if ($request->vozidlo_id != $vozidlo->vozidlo_id) {
                        $key = $final->search(function ($item) use ($id) {
                            return $item->problem_id == $id;
                        });
                        $final->pull($key);
                    }
                } else {
                    $key = $final->search(function ($item) use ($id) {
                        return $item->problem_id == $id;
                    });
                    $final->pull($key);
                }
            }
        }


        if ($request->zamestnanec_id != null) {
            foreach ($final as $problem) {

                $id = $problem->problem_id;

                $zamestnanec = DB::table('priradeny_zamestnanec')
                    ->where('problem_id', '=', $problem->problem_id)
                    ->latest('priradeny_zamestnanec_id')->first();


                if ($zamestnanec != null) {
                    if ($request->zamestnanec_id != $zamestnanec->zamestnanec_id) {
                        $key = $final->search(function ($item) use ($id) {
                            return $item->problem_id == $id;
                        });
                        $final->pull($key);
                    }
                } else {
                    $key = $final->search(function ($item) use ($id) {
                        return $item->problem_id == $id;
                    });
                    $final->pull($key);
                }
            }
        }

        $all = Problem::all();
        if($all->count() == $final->count()){
            return redirect('/problem');
        }

        $rola = Auth::user()->rola_id;
        $user_id = Auth::user()->id;
        $typy_stavov = TypStavuRieseniaProblemu::all();
        $users = User::all();
        $vsetky_vozidla = Vozidlo::all();
        $stavy_riesenia = array();
        $priradeni_zamestnanci = array();
        $priradene_vozidla = array();

        $kategorie = KategoriaProblemu::all();
        $stavyProblemu = StavProblemu::all();
        $typySTavovRieseniaProblemu = TypStavuRieseniaProblemu::all();
        $priority = Priorita::all();
        $zamestnanciAll = User::where('rola_id', '>', '2')->get();

        foreach ($final as $problem) {
            $typ = DB::table('stav_riesenia_problemu')
                ->where('problem_id', '=', $problem->problem_id)
                ->latest('stav_riesenia_problemu_id')->first();
            array_push($stavy_riesenia, $typ->typ_stavu_riesenia_problemu_id);

            $zamestnanci = DB::table('priradeny_zamestnanec')
                ->where('problem_id', '=', $problem->problem_id)
                ->latest('priradeny_zamestnanec_id')->first();
            if ($zamestnanci != null)
                array_push($priradeni_zamestnanci, $zamestnanci->zamestnanec_id);
            else
                array_push($priradeni_zamestnanci, 0);

            $vozidla = DB::table('priradene_vozidlo')
                ->where('problem_id', '=', $problem->problem_id)
                ->latest('priradene_vozidlo_id')->first();


            if ($vozidla != null)
                array_push($priradene_vozidla, $vozidla->vozidlo_id);
            else
                array_push($priradene_vozidla, 0);

        }


        if ($rola == 3)
            return view('problem.admin.admin_index')
                ->with('problems', $final)
                ->with('stavy_riesenia', $stavy_riesenia)
                ->with('typy_stavov_riesenia', $typy_stavov)
                ->with('priradeni_zamestnanci', $priradeni_zamestnanci)
                ->with('zamestnanci', $users)
                ->with('priradene_vozidla', $priradene_vozidla)
                ->with('vozidla', $vsetky_vozidla)
                ->with('kategorie', $kategorie)
                ->with('stavyProblemu', $stavyProblemu)
                ->with('typyStavovRieseniaProblemu', $typySTavovRieseniaProblemu)
                ->with('priority', $priority)
                ->with('VsetciZamestnanci', $zamestnanciAll);
        if ($rola == 4)
            return view('problem.dispecer.dispecer_index')
                ->with('problems', $final)
                ->with('stavy_riesenia', $stavy_riesenia)
                ->with('typy_stavov_riesenia', $typy_stavov)
                ->with('priradeni_zamestnanci', $priradeni_zamestnanci)
                ->with('zamestnanci', $users)
                ->with('priradene_vozidla', $priradene_vozidla)
                ->with('vozidla', $vsetky_vozidla)
                ->with('kategorie', $kategorie)
                ->with('stavyProblemu', $stavyProblemu)
                ->with('typyStavovRieseniaProblemu', $typySTavovRieseniaProblemu)
                ->with('priority', $priority)
                ->with('VsetciZamestnanci', $zamestnanciAll);
        if ($rola == 5)
            return view('problem.manazer.manazer_index')
                ->with('problems', $final)
                ->with('stavy_riesenia', $stavy_riesenia)
                ->with('typy_stavov_riesenia', $typy_stavov)
                ->with('priradeni_zamestnanci', $priradeni_zamestnanci)
                ->with('zamestnanci', $users)
                ->with('priradene_vozidla', $priradene_vozidla)
                ->with('vozidla', $vsetky_vozidla)
                ->with('kategorie', $kategorie)
                ->with('stavyProblemu', $stavyProblemu)
                ->with('typyStavovRieseniaProblemu', $typySTavovRieseniaProblemu)
                ->with('priority', $priority)
                ->with('VsetciZamestnanci', $zamestnanciAll);

    }


    /**
     * mapa vsetkych hlaseni pre pouzivatelov
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     *
     * obsahuje infowindows s detailnymi info o jednotlivych problemoch
     */
    public function mapa()
    {


        $rola = Auth::user()->rola_id;
        $problems = Problem::all();
        $typy_stavov_riesenia = TypStavuRieseniaProblemu::all();
        $popisyAll = PopisStavuRieseniaProblemu::all();
        $usersAll = User::all();
        $vozidlaAll = Vozidlo::all();


        $stavy_riesenia = array();
        $popisy_stavov_riesenia = array();
        $zamestnanciArr = array();
        $vozidlaArr = array();

        $counter = 0;

        foreach ($problems as $problem) {
            $typ = DB::table('stav_riesenia_problemu')
                ->where('problem_id', '=', $problem->problem_id)
                ->latest('stav_riesenia_problemu_id')->first();
            array_push($stavy_riesenia, $typ->typ_stavu_riesenia_problemu_id);

            $popisTable = DB::table('popis_stavu_riesenia_problemu')
                ->where('problem_id', '=', $problem->problem_id)
                ->latest('popis_stavu_riesenia_problemu_id')->first();
            if ($popisTable != null) {
                if ($popisTable->popis_stavu_riesenia_problemu_id != null)
                    $id = $popisTable->popis_stavu_riesenia_problemu_id;

                array_push($popisy_stavov_riesenia, $id);
            } else array_push($popisy_stavov_riesenia, 0);


            $priradeni_zamestnanci = DB::table('priradeny_zamestnanec')
                ->where('problem_id', '=', $problem->problem_id)
                ->latest('priradeny_zamestnanec_id')->first();
            if ($priradeni_zamestnanci != null) {
                if ($priradeni_zamestnanci->zamestnanec_id != null)
                    $id = $priradeni_zamestnanci->zamestnanec_id;

                array_push($zamestnanciArr, $id);
            } else array_push($zamestnanciArr, 0);

            $priradene_vozidla = DB::table('priradene_vozidlo')
                ->where('problem_id', '=', $problem->problem_id)
                ->latest('priradene_vozidlo_id')->first();
            if ($priradene_vozidla != null) {
                if ($priradene_vozidla->vozidlo_id != null)
                    $id = $priradene_vozidla->vozidlo_id;

                array_push($vozidlaArr, $id);
            } else array_push($vozidlaArr, 0);
        }

        if ($rola == 1) {
            return view('problem.obcan.obcan_mapa_problemov')
                ->with('problems', $problems)
                ->with('typy_stavov_riesenia', $typy_stavov_riesenia)
                ->with('stavy_riesenia', $stavy_riesenia)
                ->with('popisyAll', $popisyAll)
                ->with('popisyArr', $popisy_stavov_riesenia)
                ->with('counter', $counter);
        } else if ($rola == 3) {
            return view('problem.admin.admin_mapa_problemov')
                ->with('problems', $problems)
                ->with('typy_stavov_riesenia', $typy_stavov_riesenia)
                ->with('stavy_riesenia', $stavy_riesenia)
                ->with('popisyAll', $popisyAll)
                ->with('popisyArr', $popisy_stavov_riesenia)
                ->with('counter', $counter)
                ->with('usersAll', $usersAll)
                ->with('zamestnanciArr', $zamestnanciArr)
                ->with('vozidlaAll', $vozidlaAll)
                ->with('vozidlaArr', $vozidlaArr);
        } else if ($rola == 4) {
            return view('problem.dispecer.dispecer_mapa_problemov')
                ->with('problems', $problems)
                ->with('typy_stavov_riesenia', $typy_stavov_riesenia)
                ->with('stavy_riesenia', $stavy_riesenia)
                ->with('popisyAll', $popisyAll)
                ->with('popisyArr', $popisy_stavov_riesenia)
                ->with('counter', $counter)
                ->with('usersAll', $usersAll)
                ->with('zamestnanciArr', $zamestnanciArr)
                ->with('vozidlaAll', $vozidlaAll)
                ->with('vozidlaArr', $vozidlaArr);
        } else if ($rola == 5) {
            return view('problem.manazer.manazer_mapa_problemov')
                ->with('problems', $problems)
                ->with('typy_stavov_riesenia', $typy_stavov_riesenia)
                ->with('stavy_riesenia', $stavy_riesenia)
                ->with('popisyAll', $popisyAll)
                ->with('popisyArr', $popisy_stavov_riesenia)
                ->with('counter', $counter)
                ->with('usersAll', $usersAll)
                ->with('zamestnanciArr', $zamestnanciArr)
                ->with('vozidlaAll', $vozidlaAll)
                ->with('vozidlaArr', $vozidlaArr);
        }
    }

    public function welcomePage()
    {
        $problems = Problem::all();
        $typy_stavov_riesenia = TypStavuRieseniaProblemu::all();
        $popisyAll = PopisStavuRieseniaProblemu::all();


        $kategorie = KategoriaProblemu::all();
        $stavyProblemu = StavProblemu::all();

        $stavy_riesenia = array();
        $popisy_stavov_riesenia = array();

        $counter = 0;

        foreach ($problems as $problem) {
            $typ = DB::table('stav_riesenia_problemu')
                ->where('problem_id', '=', $problem->problem_id)
                ->latest('stav_riesenia_problemu_id')->first();
            array_push($stavy_riesenia, $typ->typ_stavu_riesenia_problemu_id);

            $popisTable = DB::table('popis_stavu_riesenia_problemu')
                ->where('problem_id', '=', $problem->problem_id)
                ->latest('popis_stavu_riesenia_problemu_id')->first();
            if ($popisTable != null) {
                if ($popisTable->popis_stavu_riesenia_problemu_id != null)
                    $id = $popisTable->popis_stavu_riesenia_problemu_id;

                array_push($popisy_stavov_riesenia, $id);
            } else array_push($popisy_stavov_riesenia, 0);
        }

        return view('welcomePage')
            ->with('problems', $problems)
            ->with('typy_stavov_riesenia', $typy_stavov_riesenia)
            ->with('stavy_riesenia', $stavy_riesenia)
            ->with('kategorie', $kategorie)
            ->with('stavy', $stavyProblemu)
            ->with('popisyAll', $popisyAll)
            ->with('popisyArr', $popisy_stavov_riesenia)
            ->with('counter', $counter);
    }



    //PRIDAL SIMON DRIENIK, ZOBRAZENIE VSETKYCH MARKEROV V ANDROID APPKE
    public function showAllProblemsAndroid($x, $zamestnanec, $stavProblemu, $kategoria, $datumOd, $datumDo, $vozidlo, $priorita1, $stavRiesenia, $role)
    {
            $problems = Problem::all();
            $arr = array();
        $poc = 0;

            foreach($problems as $problem)
	    {

	    $stav_problemu = DB::table('stav_problemu')
		->orderBy('created_at', 'desc')
                ->where('stav_problemu_id', '=', $problem->stav_problemu_id)
                ->pluck('nazov')
                ->first();
            $stav_problemu = iconv('UTF-8', 'UTF-8', $stav_problemu);

            $kategoria_problemu = DB::table('kategoria_problemu')
		->orderBy('created_at', 'desc')
		->where('kategoria_problemu_id', '=', $problem->kategoria_problemu_id)
                ->pluck('nazov')
                ->first();
            $kategoria_problemu = iconv('UTF-8', 'UTF-8', $kategoria_problemu);

	    $stav_riesenia_problemu = DB::table('stav_riesenia_problemu')
		->orderBy('created_at', 'desc')
                ->where('problem_id', '=', $problem->problem_id)
                ->pluck('typ_stavu_riesenia_problemu_id')
                ->first();
            $typ_stavu_riesenia_problemu = DB::table('typ_stavu_riesenia_problemu')
		->orderBy('created_at', 'desc')
		->where('typ_stavu_riesenia_problemu_id', '=', $stav_riesenia_problemu)
                ->pluck('nazov')
                ->first();
            $typ_stavu_riesenia_problemu = iconv('UTF-8', 'UTF-8', $typ_stavu_riesenia_problemu);

            $popis_stavu_riesenia_problemu = DB::table('popis_stavu_riesenia_problemu')
		->orderBy('created_at', 'desc')
		->where('problem_id', '=', $problem->problem_id)
                ->pluck('popis')
		->first();
	    $popis_stavu_riesenia_problemu_verejne = DB::table('popis_stavu_riesenia_problemu')
		    ->orderBy('created_at', 'desc')
		    ->where('problem_id', '=', $problem->problem_id)
		    ->pluck('verejne')
		    ->first();

	    $pouzivatel_meno = DB::table('users')
		    ->orderBy('created_at', 'desc')
		    ->where('id', '=', $problem->pouzivatel_id)
		    ->pluck('name')
		    ->first();

	    $priradeny_zamestnanec_id = DB::table('priradeny_zamestnanec')
		    ->orderBy('created_at', 'desc')
		    ->where('problem_id', '=', $problem->problem_id)
		    ->pluck('zamestnanec_id')
		    ->first();

	    if ($priradeny_zamestnanec_id != null)
	    {
		    $priradeny_zamestnanec_meno = DB::table('users')
			    ->orderBy('created_at', 'desc')
			    ->where('id','=', $priradeny_zamestnanec_id)
			    ->pluck('name')
			    ->first();
		    $priradeny_zamestnanec_meno = iconv('UTF-8', 'UTF-8', $priradeny_zamestnanec_meno);
	    }
	    else{
		    $priradeny_zamestnanec_meno = 'Nepriradeny';
	    }

	    $priorita = DB::table('priorita')
		    ->orderBy('created_at', 'desc')
		    ->where('priorita_id','=', $problem->priorita_id)
		    ->pluck('priorita')
		    ->first();
	    $priorita = iconv('UTF-8', 'UTF-8', $priorita);

	    $priradene_vozidlo_id = DB::table('priradene_vozidlo')
		    ->orderBy('created_at', 'desc')
		    ->where('problem_id', '=', $problem->problem_id)
		    ->pluck('vozidlo_id')
		    ->first();

	    if ($priradene_vozidlo_id != null){
		    $priradene_vozidlo_spz = DB::table('vozidlo')
			    ->orderBy('created_at', 'desc')
			    ->where('vozidlo_id', '=', $priradene_vozidlo_id)
			    ->pluck('SPZ')
			    ->first();

	    }
	    else{
		$priradene_vozidlo_spz = 'Nepriradene';
	    }

            if ($popis_stavu_riesenia_problemu != null)
	    {
		if ($popis_stavu_riesenia_problemu_verejne == 1 || $role > 2)
			$popis_stavu_riesenia_problemu = iconv('UTF-8', 'UTF-8', $popis_stavu_riesenia_problemu);
		else
			$popis_stavu_riesenia_problemu = 'neuvedene';
            }
            if ($popis_stavu_riesenia_problemu == null)
            {
             $popis_stavu_riesenia_problemu = 'neuvedene';
	    }



	    $created_at1 = $problem->created_at->toDateTimeString();

	    if (($x != 0 && $x == $problem->problem_id) || ($x == 0))
	    {
		    if (($zamestnanec == $priradeny_zamestnanec_meno) || ($zamestnanec == "-"))
			    if (($stavProblemu == $stav_problemu) || ($stavProblemu == "-"))
				    if (($kategoria == $kategoria_problemu) || ($kategoria == "-"))
					    if (strtotime($datumOd) < strtotime($created_at1) || $datumOd == "0000-00-00")
						    if (strtotime($datumDo) > strtotime($created_at1) || $datumDo == "0000-00-00")
							    if ($vozidlo == $priradene_vozidlo_spz || $vozidlo == "-")
								    if ($priorita1 == $priorita || $priorita1 == "-")
									    if ($stavRiesenia == $typ_stavu_riesenia_problemu || $stavRiesenia == "-")
										    {


                								$arr[$poc] = array(
                    								"position" => $problem->poloha,
                    								"id" => $problem->problem_id,
                    								"kategoria" => $kategoria_problemu,
                    								"popis" => $problem->popis_problemu,
                    								"stav_problemu" =>$stav_problemu,
                    								"stav_riesenia_problemu" =>$typ_stavu_riesenia_problemu,
            	    								"popis_riesenia_problemu" =>$popis_stavu_riesenia_problemu,
             	    								"created_at" => $created_at1,
		    								"pouzivatel" => $problem->pouzivatel_id,
		   	 							"priradeny_zamestnanec" => $priradeny_zamestnanec_meno,
		    								"priorita" => $priorita,
		    								"priradene_vozidlo" => $priradene_vozidlo_spz,
		    								"pouzivatel_meno" => $pouzivatel_meno
            									);

										$poc++;
									    }
	    }

       }

       return json_encode($arr);


    }

    public function getImgsAndroid($id)
    {
	$fotka_problemu = FotkaProblemu::all();
	$fotka_stavu_riesenia_problemu = FotkaStavuRieseniaProblemu::all();
	$popis_stavu_riesenia = PopisStavuRieseniaProblemu::all();
	$url_foto_problemu = "n";
	$url_foto_riesenia = "n";
	$popis_stavu_riesenia_problemu_id = 0;


	foreach($fotka_problemu as $foto_problemu)
	{
		if ($foto_problemu->problem_id == $id)
			$url_foto_problemu = $foto_problemu->cesta_k_suboru;
	}
	foreach($popis_stavu_riesenia as $popis)
	{
		if ($popis->problem_id == $id)
			$popis_stavu_riesenia_problemu_id = $popis->popis_stavu_riesenia_problemu_id;
	}

	if ($popis_stavu_riesenia_problemu_id != 0)
		foreach($fotka_stavu_riesenia_problemu as $fotka_riesenia)
		{
			if ($fotka_riesenia->popis_stavu_riesenia_id == $popis_stavu_riesenia_problemu_id)
				$url_foto_riesenia = $fotka_riesenia->cesta_k_suboru;
		}

	$arr[0] = array(
		"URLproblem" => $url_foto_problemu,
	        "URLriesenie" => $url_foto_riesenia
	);
	return json_encode($arr);
    }

    public function deleteProblem(Request $request)
    {
	$token = $request->authToken;
	$problemID = $request->problemID;

	$autheticated = 0;
	$users = User::all();

	foreach($users as $user)
	{
		if (($user->remember_token == $token and $user->rola_id != 1) and $user->rola_id != 2)
			$autheticated = 1;
	}

	if ($autheticated == 1)
	{
		$fotka_problemu = FotkaProblemu::all();
		$fotka_problemu_link = "";

		foreach($fotka_problemu as $foto)
		{
			if ($foto->problem_id == $problemID)
			{
				$fotka_problemu_link = $foto->cesta_k_suboru;
				$id_foto = $foto->fotka_problemu_id;
				DB::table('fotka_problemu')
					->where('fotka_problemu_id', '=', $id_foto)
					->delete();
				Storage::delete('/public' . $fotka_problemu_link);

			}
		}

		$stav_riesenia = StavRieseniaProblemu::all();

		foreach($stav_riesenia as $stav)
		{
			if ($stav->problem_id == $problemID)
			{
				DB::table('stav_riesenia_problemu')
					->where('stav_riesenia_problemu_id', '=', $stav->stav_riesenia_problemu_id)->delete();
			}
		}

		$popis_stavu_riesenia = PopisStavuRieseniaProblemu::all();

		foreach ($popis_stavu_riesenia as $popis)
		{
			if ($popis->problem_id == $problemID)
			{
				$foto_popisu = FotkaStavuRieseniaProblemu::all();
				foreach ($foto_popisu as $foto)
				{
					if ($foto->popis_stavu_riesenia_problemu_id == $popis->popis_stavu_riesenia_problemu_id)
					{
						$foto_link = $foto->cesta_k_suboru;
						$id_foto = $foto->fotka_stavu_riesenia_problemu_id;
						DB::table('fotka_stavu_riesenia_problemu')
							->where('fotka_stavu_riesenia_problemu_id', '=', $id_foto)
							->delete();
						Storage::delete('/public' . $foto_link);
					}
				}
				DB::table('popis_stavu_riesenia_problemu')
					->where('popis_stavu_riesenia_problemu_id', '=', $popis->popis_stavu_riesenia_problemu_id)
					->delete();
			}
		}

		$komentar = Komentar::all();

		foreach ($komentar as $koment)
		{
			if ($koment->problem_id == $problemID)
				DB::table('komentar')
				->where('komentar_id', '=', $koment->komentar_id)
				->delete();
		}

		$priradene_vozidlo = PriradeneVozidlo::all();

		foreach ($priradene_vozidlo as $vozidlo)
		{
			if ($vozidlo->problem_id == $problemID)
				DB::table('priradene_vozidlo')
				->where('priradene_vozidlo_id', '=', $vozidlo->priradene_vozidlo_id)
				->delete();
		}

		$priradeny_zamestnanec = PriradenyZamestnanec::all();

		foreach ($priradeny_zamestnanec as $zamestnanec)
		{
			if ($zamestnanec->problem_id == $problemID)
				DB::table('priradeny_zamestnanec')
				->where('priradeny_zamestnanec_id', '=', $zamestnanec->priradeny_zamestnanec_id)
				->delete();
		}

		$problem = Problem::all();

		foreach($problem as $pr)
		{
			if ($pr->problem_id == $problemID)
				DB::table('problem')
				->where('problem_id', '=', $problemID)
				->delete();
		}

		return 1;

	}
	else{
		return 0;
	}
    }

    public function getSpinnersAndroid(){

	 $popis_stavu_riesenia = TypStavuRieseniaProblemu::all();
		 $arr_popis_stavu_riesenia = array();
	 foreach($popis_stavu_riesenia as $popis_stavu)
	 {
		array_push($arr_popis_stavu_riesenia, iconv('UTF-8', 'UTF-8', $popis_stavu->nazov));
	 }

	 $users = User::all();
	 $arr_zamestnanci = array();
	 foreach($users as $user)
	 {
		 if($user->rola_id == 4)
			 array_push($arr_zamestnanci, iconv('UTF-8', 'UTF-8', $user->name));
	 }

	 $priority = Priorita::all();
	 $arr_priority = array();
	 foreach($priority as $priorita)
	 {
		array_push($arr_priority, iconv('UTF-8', 'UTF-8', $priorita->priorita));
	 }

	 $kategorie = KategoriaProblemu::all();
	 $arr_kategorie_problemu = array();
	 foreach($kategorie as $kategoria)
	 {
		array_push($arr_kategorie_problemu, iconv('UTF-8', 'UTF-8', $kategoria->nazov));
		 }

	 $stavy_problemu = StavProblemu::all();
	 $arr_stavy_problemu = array();
	 foreach($stavy_problemu as $stav)
	 {
		array_push($arr_stavy_problemu, iconv('UTF-8', 'UTF-8', $stav->nazov));
	 }

	 $vozidla = Vozidlo::all();
	 $arr_vozidla = array();
	 foreach($vozidla as $vozidlo)
	 {
		array_push($arr_vozidla, iconv('UTF-8', 'UTF-8', $vozidlo->SPZ));
	 }

	 array_push($arr_vozidla, 'Nepriradene');
	 array_push($arr_zamestnanci, 'Nepriradeny');

	 $arr[0] = array(
		 "zamestnanci" => $arr_zamestnanci,
		 "priority" => $arr_priority,
		 "kategorie" => $arr_kategorie_problemu,
		 "stavy_problemu" => $arr_stavy_problemu,
		 "stavy_riesenia" => $arr_popis_stavu_riesenia,
		 "vozidla" => $arr_vozidla );

	return json_encode($arr);

    }

    public function showUsersAndroid(){
	$users = User::all();
	$i = 0;
	foreach ($users as $user)
	{
		if ($user->rola_id != 2)
		{
			$arr[$i] = array(
				"name" => iconv('UTF-8', 'UTF-8', $user->name),
				"email" => $user->email,
				"created_at" => $user->created_at->toDateTimeString(),
				"role" => $user->rola_id
			);
			$i++;
		}
	}
	return json_encode($arr);

    }

    public function deleteAccountAndroid(Request $request)
    {
	    $authenticated = 0;
	    $users = User::all();
	    $notAdmin = 0;
	    $elseAdmin = 0;
	    $isHere = 0;
	    $anotherAdmin = 0;


	    foreach ($users as $user)
	    {
		    if ($user->rola_id == 3 and $user->remember_token == $request->AuthToken)
			    $authenticated = 1;
		    if ($user->email == $request->email and $user->rola_id != 3)
			    $notAdmin = 1;
		    if ($user->email == $request->email)
			    $isHere = 1;
		    if ($user->email != $request->email and $user->rola_id == 3)
			    $elseAdmin = 1;
		    if (($user->email == $request->email and $user->rola_id == 3) and $user->remember_token != $request->AuthToken)
			    $anotherAdmin = 1;
	    }

	    if ($authenticated == 1)
	    {
		    if ($isHere == 1)
		    {

			    if ($notAdmin == 1)
			    {
				    User::where('email', $request->email)->delete();
				    return 1;
			    }
			    else
			    {
				if ($anotherAdmin == 0)
				{
					if ($elseAdmin == 1)
					{
						User::where('email', $request->email)->delete();
						return 1;
					}
					else
						return 2;
				}
				else
					return 3;
			    }

		    }
		    else
			    return 0;

	    }
	    else
		    return -1;
    }

    public function editAccountAndroid(Request $request)
    {
	$authenticated = 0;
	$users = User::all();
	$isHere = 0;
	$isAdmin = 0;
	$anotherAdmin = 0;
	$elseAdmin = 0;

	foreach ($users as $user)
	{
		if ($user->rola_id == 3 and $user->remember_token == $request->AuthToken)
			$authenticated = 1;
		if ($user->email == $request->email)
			$isHere = 1;
		if ($user->email == $request->email and $user->rola_id == 3)
			$isAdmin = 1;
		if (($user->email == $request->email and $user->rola_id == 3) and $user->remember_token != $request->AuthToken)
			$elseAdmin = 1;
		if ($user->email != $request->email and $user->rola_id == 3)
			$anotherAdmin = 1;
	}

	if ($authenticated == 1)
	{
		if ($isHere == 1)
		{
			if ($isAdmin == 1)
			{
				if ($elseAdmin == 0)
				{
					if ($anotherAdmin == 1)
					{
					$user1 = User::where('email', $request->email)->first();
					$user1->rola_id = $request->rola;
					$user1->save();
					return 1;
					}
					else
						return 3;
				}
				else
					return 2;

			}
			else
			{
				$user1 = User::where('email', $request->email)->first();
				$user1->rola_id = $request->rola;
				$user1->save();
				return 1;
			}
		}
		else
			return 0;

	}
	else
		return -1;


    }



    public function unregisteredAddRecordAndroid($poloha, $popis_problemu, $kategoria_problemu, $stav_problemu, $imgId, $idOfUser)
    {
        $arr = array();


            if ($poloha != null)
        {
        $kategoria_problemu_id = DB::table('kategoria_problemu')
            ->where('nazov', $kategoria_problemu)
            ->pluck('kategoria_problemu_id')
            ->first();

        $stav_problemu_id = DB::table('stav_problemu')
            ->where('nazov', $stav_problemu)
            ->pluck('stav_problemu_id')
            ->first();

    $arr = array(
        'pouzivatel_id' => $idOfUser,
            'poloha' => $poloha,
        'popis_problemu' => $popis_problemu,
        'kategoria_problemu_id' => $kategoria_problemu_id,
        'stav_problemu_id' => $stav_problemu_id
	);



    	Problem::create($arr);
	$last = DB::table('problem')->latest('problem_id')->first();
	StavRieseniaProblemu::create(['problem_id' => $last->problem_id, 'typ_stavu_riesenia_problemu_id' => 1]);

	if ($imgId != 0){
		DB::table('fotka_problemu')
			->where('fotka_problemu_id', $imgId)
			->update(['problem_id' => $last->problem_id]);

	}

	//return 1;





	}

	return 1;


    }

    public function storeProblemImgAndroid(Request $request){

	/*$validated = $request->validate([
			'name' => 'string|max:40',
			'image'=> 'mimes:jpeg,png|max:100070'
		]);*/


			$extension = $request->file('image')->extension();
			$request->image->storeAs('/public', $request->image.".".$extension);
			$url = Storage::url($validated['name'].".".$extension);
			$arr = array('cesta_k_suboru' => $url, 'problem_id' => 1);
			FotkaProblemu::create($arr);

			$last = DB::table('fotka_problemu')->latest('fotka_problemu_id')->first();

			//return $last->fotka_problemu_id;
			return $extension;

    }

    public function historyAndroid(Request $request){

	$attribute = $request->attribute;
	$problemID = $request->problemID;
	$arr = array();

	if ($attribute == 0)
	{
		$items = FotkaStavuRieseniaProblemu::all();
	}
	if ($attribute == 1)
	{
		$items = Komentar::all();
	}
	if ($attribute == 2)
	{
		$items = PopisStavuRieseniaProblemu::all();
	}
	if ($attribute == 3)
	{
		$items = PriradeneVozidlo::all();
	}
	if ($attribute == 4)
	{
		$items = PriradenyZamestnanec::all();
	}
	if ($attribute == 5)
	{
		$items = StavRieseniaProblemu::all();
	}

	$i = 0;
	$pocet = 0;
	$date = "";
	$name = "";

	if ($attribute == 0)
	{
		$popisy = PopisStavuRieseniaProblemu::all();
		foreach ($popisy as $popis)
		{
			if ($popis->problem_id == $problemID)
			{
				foreach ($items as $item)
				{
					if ($item->popis_stavu_riesenia_id == $popis->popis_stavu_riesenia_problemu_id)
					{
						$arr[$pocet] = array(
							'meno' => $item->cesta_k_suboru,
							'date' => $item->created_at->toDateTimeString(),
							'user' => "none"
						);
						$pocet++;
					}
				}
			}

		}

	}

	if ($attribute == 1)
	{
		foreach ($items as $item)
		{
			if ($item->problem_id == $problemID)
			{
				$text = $item->komentar;
				$datum = $item->created_at->toDateTimeString();
				$users = User::all();
				foreach ($users as $user)
				{
					if ($user->id == $item->pouzivatel_id)
						$uzivatel = $user->name;
				}
				$arr[$pocet] = array(
					'meno' => $text,
					'date' => $datum,
					'user' => $uzivatel
				);
				$pocet++;
			}
		}
	}

	if ($attribute == 2)
	{
		foreach ($items as $item)
		{
			if ($item->problem_id == $problemID && ($item->verejne == 1 || $request->role  > 2))
			{
				$arr[$pocet] = array(
					'meno' => $item->popis,
					'date' => $item->created_at->toDateTimeString(),
					'user' => "none"
				);
				$pocet++;
			}
		}
	}

	if ($attribute == 3)
	{
		foreach ($items as $item)
		{
			if ($item->problem_id == $problemID)
			{
				$date = $item->created_at->toDateTimeString();
				$vozidla = Vozidlo::all();
				foreach ($vozidla as $vozidlo)
				{
					if ($vozidlo->vozidlo_id == $item->vozidlo_id)
					{
						$meno = $vozidlo->SPZ;
					}
				}
				$arr[$pocet] = array(
					'meno' => $meno,
					'date' => $date,
					'user' => "none"
				);
				$pocet++;
			}
		}
	}

	if ($attribute == 4)
	{
		foreach ($items as $item)
		{
			if ($item->problem_id == $problemID)
			{
				$users = User::all();
				foreach ($users as $user)
				{
					if ($user->id == $item->zamestnanec_id)
						$name = $user->name;
				}
				$arr[$pocet] = array(
					'meno' => $name,
					'date' => $item->created_at->toDateTimeString(),
					'user' => "none"
				);
				$pocet++;

			}
		}
	}

	if ($attribute == 5)
	{
		foreach ($items as $item)
		{
			if ($item->problem_id == $problemID)
			{
				$typy = TypStavuRieseniaProblemu::all();
				foreach ($typy as $typ)
				{
					if ($typ->typ_stavu_riesenia_problemu_id == $item->typ_stavu_riesenia_problemu_id)
						$meno = $typ->nazov;
				}
				$arr[$pocet] = array(
					'meno' => $meno,
					'date' => $item->created_at->toDateTimeString(),
					'user' => "none"
				);
				$pocet++;
			}
		}
	}

	return json_encode($arr);

    }

    public function uploadRiesenieImage(Request $request)
    {
	$users = User::all();
	$token = $request->token;
	$riesenieID = (int)$request->riesenieID;
	$autheticated = 0;

	foreach($users as $user)
	{
		if ($user->remember_token == $token and ($user->rola_id == 3 || $user->rola_id == 4 || $user->rola_id == 5))
			$autheticated = 1;
	}

	if ($autheticated == 1)
	{
		$request->image->storeAs('/public', $request->name.".".$request->image->extension());
		$url = Storage::url($request->name.".".$request->image->extension());
		$arr = array('cesta_k_suboru' => $url, 'popis_stavu_riesenia_id' => $riesenieID);
		FotkaStavuRieseniaProblemu::create($arr);
		return 1;


	}
	else
	{
		return 0;
	}


    }

    public function comment(Request $request)
    {
	$users = User::all();
	$token = $request->token;
	$problemID = $request->idProblem;
	$komentText = $request->komentText;
	$userID = $request->userID;
	$autheticated = 0;

	if ($userID > 1)
	{
		foreach ($users as $user)
		{
			if ($user->remember_token == $token && $user->id == $userID)
				$autheticated = 1;
		}
	}
	else
	{
		$autheticated = 1;
	}

	if ($autheticated == 1)
	{
		Komentar::create(['je_zamestnanec' => 0, 'komentar' => $komentText, 'problem_id' => $problemID, 'pouzivatel_id' => $userID, 'zverejnitelnost' => 1]);
		return 1;
	}
	else{
		return 5;
	}
    }


    public function editProblem(Request $request)
    {
	    $users = User::all();
	    $token = $request->token;
	    $autheticated = 0;

	    foreach($users as $user)
	    {
		if ($user->remember_token == $token and ($user->rola_id == 3 || $user->rola_id == 4 || $user->rola_id == 5))
			$autheticated = 1;
	    }

	    if ($autheticated == 1)
	    {


		if ($request->zamestanec != "n")
		{
			foreach ($users as $user)
			{
				if (iconv('UTF-8', 'ASCII//TRANSLIT', $user->name) == $request->zamestnanec)
				{
					PriradenyZamestnanec::create(['problem_id' => $request->problemID, 'zamestnanec_id' => $user->id]);
				}
			}
		}
		if ($request->priorita != "n")
		{
			$priority = Priorita::all();
			foreach ($priority as $priorita)
			{
				if (iconv('UTF-8', 'ASCII//TRANSLIT', $priorita->priorita) == $request->priorita)
					Problem::where('problem_id', $request->problemID)->update(['priorita_id' => $priorita->priorita_id]);
			}
		}
		if ($request->kategoria != "n")
		{
			$kategorie = KategoriaProblemu::all();
			foreach ($kategorie as $kategoria)
			{
				if(iconv('UTF-8', 'ASCII//TRANSLIT', $kategoria->nazov) == $request->kategoria)
					Problem::where('problem_id', $request->problemID)->update(['kategoria_problemu_id' => $kategoria->kategoria_problemu_id]);
			}
		}
		if ($request->stavProblemu != "n")
		{
			$stavy = StavProblemu::all();
			foreach ($stavy as $stav)
			{
				if (iconv('UTF-8', 'ASCII//TRANSLIT', $stav->nazov) == $request->stavProblemu)
					Problem::where('problem_id', $request->problemID)->update(['stav_problemu_id' => $stav->stav_problemu_id]);
			}
		}
		if ($request->stavRiesenia != "n")
		{
			$stavy = TypStavuRieseniaProblemu::all();
			foreach ($stavy as $stav)
			{
				if (iconv('UTF-8', 'ASCII//TRANSLIT', $stav->nazov) == $request->stavRiesenia)
					StavRieseniaProblemu::create(['problem_id' => $request->problemID, 'typ_stavu_riesenia_problemu_id' => $stav->typ_stavu_riesenia_problemu_id]);
			}
		}
		if ($request->vozidlo != "n")
		{
			$vozidla = Vozidlo::all();
			foreach($vozidla as $vozidlo)
			{
				if (iconv('UTF-8', 'ASCII//TRANSLIT', $vozidlo->SPZ) == $request->vozidlo)
					PriradeneVozidlo::create(['problem_id' => $request->problemID, 'vozidlo_id' => $vozidlo->vozidlo_id]);
			}
		}
		if ($request->popisRiesenia != "n")
		{
			PopisStavuRieseniaProblemu::create(['problem_id' => $request->problemID, 'popis' => $request->popisRiesenia]);
			$last = DB::table('popis_stavu_riesenia_problemu')->latest('popis_stavu_riesenia_problemu_id')->first();

			DB::table('popis_stavu_riesenia_problemu')->where('popis_stavu_riesenia_problemu_id', $last->popis_stavu_riesenia_problemu_id)->update(['verejne' => $request->verejne]);

			return $last->popis_stavu_riesenia_problemu_id;

		}
		else
			return 0;
	    }
	    else
		    return -1;

    }
    //KONIEC DRIENIK
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rola = Auth::user()->rola_id;
        $kategorie = KategoriaProblemu::all();
        $stavy = StavProblemu::all();

        if (($rola == 1) || ($rola == 2)) {

            return view('problem.obcan.obcan_create')->with('kategorie', $kategorie)->with('stavy', $stavy);
        } else if ($rola == 3) {
            return view('problem.admin.admin_create')->with('kategorie', $kategorie)->with('stavy', $stavy);
        } else if ($rola == 4) {
            return view('problem.dispecer.dispecer_create')->with('kategorie', $kategorie)->with('stavy', $stavy);
        } else if ($rola == 5) {
            return view('problem.manazer.manazer_create')->with('kategorie', $kategorie)->with('stavy', $stavy);
        }
    }

    public function welcomePageCreate()
    {
        $kategorie = KategoriaProblemu::all();
        $stavy = StavProblemu::all();
        return view('problem.nezaregistrovanyObcan.nezaregistrovanyObcan_create')
            ->with('kategorie', $kategorie)->with('stavy', $stavy);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     *
     * validacia povinnych polí
     * pridanie user id tvorcu problemu do requestu
     * vytvorenie zaznamu problemu v DB
     * pre kazdy vytvoreny problem, vytvorim zaznam v stav_riesenia_problemu s hodnotou prijate
     */
    public function store(Request $request)
    {

        $request->validate([
            'poloha' => 'required',
            'popis_problemu' => 'required'
        ]);


        $request->request->add(['pouzivatel_id' => Auth::user()->id]);


        Problem::create($request->all());

        $last = DB::table('problem')->latest('problem_id')->first();
        StavRieseniaProblemu::create(['problem_id' => $last->problem_id, 'typ_stavu_riesenia_problemu_id' => 1]);


        return redirect('problem');
        //->with('success', 'Hlasenie bolo prijate!');
    }

    public function welcomePageStore(Request $request)
    {
        $request->validate([
            'poloha' => 'required',
            'popis_problemu' => 'required'
        ]);

        $request->request->add(['pouzivatel_id' => '1']);
        Problem::create($request->all());

        $last = DB::table('problem')->latest('problem_id')->first();
        StavRieseniaProblemu::create(['problem_id' => $last->problem_id, 'typ_stavu_riesenia_problemu_id' => 1]);


        return redirect('/');
        //->with('success', 'Hlasenie bolo prijate!');

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Problem $problem)
    {
        $rola = Auth::user()->rola_id;

        $typ = StavRieseniaProblemu::where('problem_id', '=', $problem->problem_id)
            ->latest('stav_riesenia_problemu_id')->first();
        $popis_riesenia = PopisStavuRieseniaProblemu::where('problem_id', '=', $problem->problem_id)
            ->latest('popis_stavu_riesenia_problemu_id')->first();


        if ($rola == 1) {
            return view('problem.obcan.obcan_detail', compact('problem', $problem))
                ->with('stav_riesenia_problemu', $typ)
                ->with('popis_stavu_riesenia', $popis_riesenia);
        } else {
            $zamestnanec = PriradenyZamestnanec::where('problem_id', '=', $problem->problem_id)
                ->latest('priradeny_zamestnanec_id')->first();

            $vozidlo = PriradeneVozidlo::where('problem_id', '=', $problem->problem_id)
                ->latest('priradene_vozidlo_id')->first();

            if ($rola == 3) {
                return view('problem.admin.admin_detail', compact('problem', $problem))
                    ->with('stav_riesenia_problemu', $typ)
                    ->with('popis_stavu_riesenia', $popis_riesenia)
                    ->with('priradeny_zamestnanec', $zamestnanec)
                    ->with('priradene_vozidlo', $vozidlo);
            }
            if ($rola == 4) {
                return view('problem.dispecer.dispecer_detail', compact('problem', $problem))
                    ->with('stav_riesenia_problemu', $typ)
                    ->with('popis_stavu_riesenia', $popis_riesenia)
                    ->with('priradeny_zamestnanec', $zamestnanec)
                    ->with('priradene_vozidlo', $vozidlo);
            }
            if ($rola == 5) {
                return view('problem.manazer.manazer_detail', compact('problem', $problem))
                    ->with('stav_riesenia_problemu', $typ)
                    ->with('popis_stavu_riesenia', $popis_riesenia)
                    ->with('priradeny_zamestnanec', $zamestnanec)
                    ->with('priradene_vozidlo', $vozidlo);
            }
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Problem $problem)
    {
        $priority = Priorita::all();
        $kraje = Kraj::all();
        $katastralne_uzemia = KatastralneUzemie::all();
        $obce = Obec::all();
        $spravcovia = Spravca::all();
        $typy_stavov_riesenia_problemu = TypStavuRieseniaProblemu::all();
        $kategorie = KategoriaProblemu::all();
        $stavy_problemu = StavProblemu::all();
        $vozidla = Vozidlo::all();


        $priradeny_zamestanec = PriradenyZamestnanec::where('problem_id', '=', $problem->problem_id)
            ->latest('priradeny_zamestnanec_id')->first();

        $stav = StavRieseniaProblemu::where('problem_id', '=', $problem->problem_id)
            ->latest('stav_riesenia_problemu_id')->first();

        $priradene_vozidlo = PriradeneVozidlo::where('problem_id', '=', $problem->problem_id)
            ->latest('priradene_vozidlo_id')->first();

        $priradeny_popis_riesenia = PopisStavuRieseniaProblemu::where('problem_id', '=', $problem->problem_id)
            ->latest('popis_stavu_riesenia_problemu_id')->first();

        $dispeceri = DB::table('users')->where('rola_id', '=', 4)->get();

        $rola = Auth::user()->rola_id;

        if ($rola == 3) {
            return view('problem.admin.admin_edit', compact('problem', $problem))
                ->with('priority', $priority)
                ->with('kraje', $kraje)
                ->with('katastralne_uzemia', $katastralne_uzemia)
                ->with('obce', $obce)
                ->with('spravcovia', $spravcovia)
                ->with('typy_stavov_riesenia_problemu', $typy_stavov_riesenia_problemu)
                ->with('stav_riesenia_problemu', $stav)
                ->with('kategorie', $kategorie)
                ->with('vozidla', $vozidla)
                ->with('priradene_vozidlo', $priradene_vozidlo)
                ->with('stavy_problemu', $stavy_problemu)
                ->with('popis_stavu_riesenia', $priradeny_popis_riesenia)
                ->with('dispeceri', $dispeceri)
                ->with('priradeny_zamestnanec', $priradeny_zamestanec);
        } else if ($rola == 4) {
            return view('problem.dispecer.dispecer_edit', compact('problem', $problem))
                ->with('priority', $priority)
                ->with('kraje', $kraje)
                ->with('katastralne_uzemia', $katastralne_uzemia)
                ->with('obce', $obce)
                ->with('spravcovia', $spravcovia)
                ->with('typy_stavov_riesenia_problemu', $typy_stavov_riesenia_problemu)
                ->with('stav_riesenia_problemu', $stav)
                ->with('kategorie', $kategorie)
                ->with('vozidla', $vozidla)
                ->with('priradene_vozidlo', $priradene_vozidlo)
                ->with('stavy_problemu', $stavy_problemu)
                ->with('popis_stavu_riesenia', $priradeny_popis_riesenia)
                ->with('dispeceri', $dispeceri)
                ->with('priradeny_zamestnanec', $priradeny_zamestanec);
        } else if ($rola == 5) {
            return view('problem.manazer.manazer_edit', compact('problem', $problem))
                ->with('priority', $priority)
                ->with('kraje', $kraje)
                ->with('katastralne_uzemia', $katastralne_uzemia)
                ->with('obce', $obce)
                ->with('spravcovia', $spravcovia)
                ->with('typy_stavov_riesenia_problemu', $typy_stavov_riesenia_problemu)
                ->with('stav_riesenia_problemu', $stav)
                ->with('kategorie', $kategorie)
                ->with('vozidla', $vozidla)
                ->with('priradene_vozidlo', $priradene_vozidlo)
                ->with('stavy_problemu', $stavy_problemu)
                ->with('popis_stavu_riesenia', $priradeny_popis_riesenia)
                ->with('dispeceri', $dispeceri)
                ->with('priradeny_zamestnanec', $priradeny_zamestanec);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Problem $problem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Problem $problem)
    {

        $stav = StavRieseniaProblemu::where('problem_id', '=', $problem->problem_id)
            ->latest('stav_riesenia_problemu_id')->first();
        $priradene_vozidlo = PriradeneVozidlo::where('problem_id', '=', $problem->problem_id)
            ->latest('priradene_vozidlo_id')->first();
        $aktualny_popis = PopisStavuRieseniaProblemu::where('problem_id', '=', $problem->problem_id)
            ->latest('popis_stavu_riesenia_problemu_id')->first();
        $aktualny_priradeny_zamestnanec = PriradenyZamestnanec::where('problem_id', '=', $problem->problem_id)
            ->latest('priradeny_zamestnanec_id')->first();


        if ($request->priorita_id != $problem->priorita_id) {
            $problem->priorita_id = $request->priorita_id;
        }
        if ($request->poloha != $problem->poloha) {
            $problem->poloha = $request->poloha;
        }
        if ($request->kategoria_problemu_id != $problem->kategoria_problemu_id) {
            $problem->kategoria_problemu_id = $request->kategoria;
        }
        if ($request->stav_problemu_id != $problem->stav_problemu_id) {
            $problem->stav_problemu_id = $request->stav_problemu_id;
        }
        if ($stav->typ_stavu_riesenia_problemu_id != $request->stav_riesenia_problemu_id) {
            StavRieseniaProblemu::create(['problem_id' => $problem->problem_id,
                'typ_stavu_riesenia_problemu_id' => $request->stav_riesenia_problemu_id]);
        }
        if ($request->priradene_vozidlo_id != 0) {
            if ($priradene_vozidlo == null) {
                PriradeneVozidlo::create(['problem_id' => $problem->problem_id,
                    'vozidlo_id' => $request->priradene_vozidlo_id]);
            } else
                if ($priradene_vozidlo->vozidlo_id != $request->priradene_vozidlo_id) {
                    PriradeneVozidlo::create(['problem_id' => $problem->problem_id,
                        'vozidlo_id' => $request->priradene_vozidlo_id]);
                }
        }
        if ($request->popis_stavu_riesenia_problemu != null) {
            if ($aktualny_popis == null) {
                PopisStavuRieseniaProblemu::create(['popis' => $request->popis_stavu_riesenia_problemu,
                    'problem_id' => $problem->problem_id]);
            } else
                if ($request->popis_stavu_riesenia_problemu != $aktualny_popis->popis) {
                    PopisStavuRieseniaProblemu::create(['popis' => $request->popis_stavu_riesenia_problemu,
                        'problem_id' => $problem->problem_id]);
                }
        }

        if ((Auth::user()->rola_id) == 5 || (Auth::user()->rola_id) == 3) {
            if ($request->priradeny_zamestnanec_id != null) {
                if ($request->priradeny_zamestnanec_id != 0) {
                    if ($aktualny_priradeny_zamestnanec == null) {
                        PriradenyZamestnanec::create(['zamestnanec_id' => $request->priradeny_zamestnanec_id,
                            'problem_id' => $problem->problem_id]);
                    } else
                        if ($request->priradeny_zamestnanec_id != $aktualny_priradeny_zamestnanec) {
                            PriradenyZamestnanec::create(['zamestnanec_id' => $request->priradeny_zamestnanec_id,
                                'problem_id' => $problem->problem_id]);
                        }
                }
            }
        }

        $problem->save();
        return redirect('problem');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Problem $problem
     * @return \Illuminate\Http\Response
     */
    public function destroy(Problem $problem)
    {
        $problem = Problem::find($problem->problem_id);
        $problem->delete();

        return redirect('problem');
    }


    public function priradeneProblemyDispecerovi(User $user)
    {

        $dispecer = Auth::user()->id;
        $priradenyZamestnanec = PriradenyZamestnanec::where('zamestnanec_id', '=', $dispecer);


    }

    public function priradeniZamestnanci(Problem $problem)
    {
        $zamestnanci = PriradenyZamestnanec::where('problem_id', '=', $problem->problem_id)->get();

        $rola = Auth::user()->rola_id;

        if ($rola == 4) {
            return view('problem.dispecer.historia.dispecer_priradeniZamestnanci',
                compact('problem', $problem))->with('zamestnanci', $zamestnanci);
        } else if ($rola == 3) {
            return view('problem.admin.historia.admin_priradeniZamestnanci',
                compact('problem', $problem))->with('zamestnanci', $zamestnanci);
        } else if ($rola == 5) {
            return view('problem.manazer.historia.manazer_priradeniZamestnanci',
                compact('problem', $problem))->with('zamestnanci', $zamestnanci);
        }
    }

    public function priradeneVozidla(Problem $problem)
    {
        $vozidla = PriradeneVozidlo::where('problem_id', '=', $problem->problem_id)->get();

        $rola = Auth::user()->rola_id;

        if ($rola == 4) {
            return view('problem.dispecer.historia.dispecer_priradeneVozidla',
                compact('problem', $problem))->with('vozidla', $vozidla);
        } else if ($rola == 3) {
            return view('problem.admin.historia.admin_priradeneVozidla',
                compact('problem', $problem))->with('vozidla', $vozidla);
        } else if ($rola == 5) {
            return view('problem.manazer.historia.manazer_priradeneVozidla',
                compact('problem', $problem))->with('vozidla', $vozidla);
        }
    }

    public function stavyRieseniaProblemu(Problem $problem)
    {
        $stavy = StavRieseniaProblemu::where('problem_id', '=', $problem->problem_id)->get();

        $rola = Auth::user()->rola_id;

        if ($rola == 4) {
            return view('problem.dispecer.historia.dispecer_stavyRieseniaProblemu',
                compact('problem', $problem))->with('stavy', $stavy);
        } else if ($rola == 3) {
            return view('problem.admin.historia.admin_stavyRieseniaProblemu',
                compact('problem', $problem))->with('stavy', $stavy);
        } else if ($rola == 5) {
            return view('problem.manazer.historia.manazer_stavyRieseniaProblemu',
                compact('problem', $problem))->with('stavy', $stavy);
        }
    }

    public function popisyStavovRieseniaProblemu(Problem $problem)
    {
        $popisy = PopisStavuRieseniaProblemu::where('problem_id', '=', $problem->problem_id)->get();

        $rola = Auth::user()->rola_id;

        if ($rola == 4) {
            return view('problem.dispecer.historia.dispecer_popisyStavovRieseniaProblemu',
                compact('problem', $problem))->with('popisy', $popisy);
        } else if ($rola == 3) {
            return view('problem.admin.historia.admin_popisyStavovRieseniaProblemu',
                compact('problem', $problem))->with('popisy', $popisy);
        } else if ($rola == 5) {
            return view('problem.manazer.historia.manazer_popisyStavovRieseniaProblemu',
                compact('problem', $problem))->with('popisy', $popisy);
        }
    }
}
