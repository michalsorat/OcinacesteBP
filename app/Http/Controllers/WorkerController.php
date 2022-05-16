<?php

namespace App\Http\Controllers;

use App\Models\FotkaProblemu;
use App\Models\FotkaRieseniaProblemu;
use App\Models\KategoriaProblemu;
use App\Models\PopisStavuRieseniaProblemu;
use App\Models\Priorita;
use App\Models\Problem;
use App\Models\ProblemHistoryRecord;
use App\Models\StavProblemu;
use App\Models\StavRieseniaProblemu;
use App\Models\TypStavuRieseniaProblemu;
use App\Models\WorkingGroup;
use App\Models\WorkingGroupHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $solStatusTypes = TypStavuRieseniaProblemu::all();
        $priorities = Priorita::all();
        $solStatusAssignedProblems = array();
        $assignedProblems = array();
        if ($user->working_group_id == 0) {
            $workingGroup = null;
        }
        else {
            $workingGroup = WorkingGroup::with('users')
                                        ->with('vehicle')
                                        ->with('assignedCategories')
                                        ->with(['assignedProblems' => function($q){
                                            $q->with('StavRieseniaProblemu');
                                        }])
                                        ->where('id', '=', $user->working_group_id)
                                        ->firstOrFail();

            foreach ($workingGroup->assignedProblems as $problem) {
                if ($problem->StavRieseniaProblemu->typ_stavu_riesenia_problemu_id == 3) {
                    array_push($assignedProblems, $problem);
                    $typ = DB::table('stav_riesenia_problemu')
                        ->where('problem_id', '=', $problem->problem_id)
                        ->latest('stav_riesenia_problemu_id')->first();
                    array_push($solStatusAssignedProblems, $typ->typ_stavu_riesenia_problemu_id);
                }

            }
        }

        return view('views.worker.worker_assignedProblems')
            ->with('workingGroup', $workingGroup)
            ->with('assignedProblems', $assignedProblems)
            ->with('solStatusAssignedProblems', $solStatusAssignedProblems)
            ->with('priorities', $priorities)
            ->with('solStatusTypes', $solStatusTypes);
    }

    public function assignedProblemDetail($id) {
        $problemFull = Problem::with('problemImage')->with('problemHistory')->where('problem_id', '=', $id)->firstOrFail();
        $categories = KategoriaProblemu::all();
        $problemStates = StavProblemu::all();
        $typ = StavRieseniaProblemu::where('problem_id', '=', $id)
            ->latest('stav_riesenia_problemu_id')->first();
        $popis_riesenia = PopisStavuRieseniaProblemu::where('problem_id', '=', $id)
            ->latest('popis_stavu_riesenia_problemu_id')->first();

        return view('views.worker.worker_solveProblem')
            ->with('problem', $problemFull)
            ->with('stav_riesenia_problemu', $typ)
            ->with('popis_riesenia', $popis_riesenia)
            ->with('problemStates', $problemStates)
            ->with('categories', $categories);
    }

    public function solveProblem(Request $request, Problem $problem) {
        $request->validate([
            'file' => 'mimes:jpeg,bmp,png'
        ]);
        if ($request->hasFile('file')) {
            $uploadedImage = $request->file('file');
            $fileName = date('Y-m-d-') . $uploadedImage->hashName();
            $uploadedImage->storeAs('problemImages', $fileName, 'public');
            FotkaRieseniaProblemu::create(['problem_id' => $problem->problem_id, 'nazov_suboru' => $fileName]);
            return response()->json(['success'=>$fileName]);
        }

        if ($request->solutionDesc != null) {
            PopisStavuRieseniaProblemu::create(['problem_id' => $problem->problem_id, 'popis' => $request->solutionDesc]);
            $latestSolState = StavRieseniaProblemu::latest()->where('problem_id', '=', $problem->problem_id)->first();
            StavRieseniaProblemu::create(['problem_id' => $problem->problem_id, 'typ_stavu_riesenia_problemu_id' => 2]); //čaká na schválenie
            ProblemHistoryRecord::create(['problem_id' => $problem->problem_id, 'type' => 'Pridaný popis riešenia problému', 'description' => '']);
            ProblemHistoryRecord::create(['problem_id' => $problem->problem_id, 'type' => 'Zmena stavu riešenia', 'description' => $latestSolState->TypStavuRieseniaProblemu['nazov'].' -> Čaká na schválenie']);
            WorkingGroupHistory::create(['working_group_id' => $problem->working_group_id, 'type' => 'Problém čaká na schválenie', 'description' => 'Problém ID '.$problem->problem_id.' -> čaká na schválenie']);
            $problem->save();

            return redirect()->route('worker.index')
                ->with('status', 'Problem úspešne odoslaný na schválenie!');
        }
        else {
            return redirect()->back()
                ->with('error', 'Popis riešenia problému nemôže byť prázdny!');
        }
    }

    public function workingGroupDetail() {
        $user = Auth::user();
        $categories = KategoriaProblemu::all();
        if ($user->working_group_id == 0) {
            $workingGroup = null;
        }
        else {
            $workingGroup = WorkingGroup::with('users')
                ->with('vehicle')
                ->with('assignedCategories')
                ->where('id', '=', $user->working_group_id)
                ->firstOrFail();
        }

        return view('views.worker.worker_workingGroup')
            ->with('categories', $categories)
            ->with('workingGroup', $workingGroup);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
    public function destroy($id)
    {
        //
    }
}
