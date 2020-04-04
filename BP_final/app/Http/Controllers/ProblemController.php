<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Problem;
use App\Models\KategoriaProblemu;
use App\Models\Priorita;
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
            $problems = Problem::with( 'KategoriaProblemu', 'StavProblemu', 'StavRieseniaProblemu.TypStavuRieseniaProblemu', 'users' )
                ->where('pouzivatel_id', $user_id)
                ->get();

            return view('problem/index_obcania')->with('problems', $problems);
        }
        else

            $problems = Problem::with( 'KategoriaProblemu', 'StavProblemu', 'StavRieseniaProblemu.TypStavuRieseniaProblemu', 'Priorita',
                'users','PriradenyZamestnanec.users', 'PriradeneVozidlo.Vozidlo', 'Cesta.Kraj', 'Cesta.Obec', 'Cesta.KatastralneUzemie', 'Cesta.Spravca',
                'PopisStavuRieseniaProblemu')
                ->get();
            $problems = Problem::paginate(5);





            return view('problem/index_zamestnanci')->with ('problems', $problems);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('problem.create');
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
        return view('problem.update', compact('problem', $problem));
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
