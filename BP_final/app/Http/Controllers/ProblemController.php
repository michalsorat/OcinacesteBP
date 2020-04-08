<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Problem;
use App\Models\KategoriaProblemu;
use App\Models\StavProblemu;
use App\Models\Priorita;
use App\Models\Kraj;
use App\Models\KatastralneUzemie;
use App\Models\Obec;
use App\Models\Spravca;
use App\Models\TypStavuRieseniaProblemu;
use App\Models\StavRieseniaProblemu;
use Illuminate\Support\Facades\Auth;


class ProblemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rola = Auth::user()->rola_id;
        $user_id = Auth::user()->id;

        if(($rola==1) || ($rola == 2)) {

            $problems = Problem::where('pouzivatel_id', '=', $user_id)->paginate(5);

            return view('problem/index_obcania')->with('problems', $problems);
        }
        else

            $problems = Problem::paginate(5);
            return view('problem/index_zamestnanci')->with ('problems', $problems);
    }

    public function mapa(){

        $problems = Problem::all();
        return view('problem/mapa_problemov')->with('problems', $problems);
    }

    public function welcomePage(){

        $problems = Problem::all();
        return view('welcomePage')->with('problems', $problems);
    }

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

        if(($rola==1) || ($rola == 2)) {

            return view('problem.create')->with('kategorie', $kategorie)->with('stavy', $stavy);
        }
        else{
            return view('problem.create_admin')->with('kategorie', $kategorie)->with('stavy', $stavy);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'poloha' => 'required',
            'popis_problemu' => 'required'
        ]);

        $request->request->add(['pouzivatel_id' => Auth::user()->id]);
        Problem::create($request->all());


        return redirect('problem');
        //->with('success', 'Hlasenie bolo prijate!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Problem $problem)

    {
        return view('problem.detail', compact('problem', $problem));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Problem $problem)
    {
        $priority = Priorita::all();
        $kraje = Kraj::all();
        $katastralne_uzemia = KatastralneUzemie::all();
        $obce = Obec::all();
        $spravcovia = Spravca::all();
        $stavy_riesenia_problemu = TypStavuRieseniaProblemu::all();
        $kategorie = KategoriaProblemu::all();
        $stavy_problemu = StavProblemu::all();


        return view('problem.edit', compact('problem', $problem))
            ->with('priority', $priority)
            ->with('kraje', $kraje)
            ->with('katastralne_uzemia', $katastralne_uzemia)
            ->with('obce', $obce)
            ->with('spravcovia', $spravcovia)
            ->with('stavy_riesenia_problemu', $stavy_riesenia_problemu)
            ->with('kategorie', $kategorie)
            ->with('stavy_problemu', $stavy_problemu);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Problem $problem)
    {
        $problem = Problem::find($problem->problem_id);
        $problem->delete();

        return redirect('problem');
    }
}
