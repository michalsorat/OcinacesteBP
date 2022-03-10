<?php

namespace App\Http\Controllers;

use App\Charts\WorkingGroupChart;
use App\Models\AssignedCategoriesToGroup;
use App\Models\KategoriaProblemu;
use App\Models\Priorita;
use App\Models\Problem;
use App\Models\StavProblemu;
use App\Models\StavRieseniaProblemu;
use App\Models\TypStavuRieseniaProblemu;
use App\Models\Vozidlo;
use App\Models\WorkingGroup;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use SplFixedArray;

class ManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $users = User::where('rola_id', '=', '4')->get();
        $availUsers = User::where('rola_id', '=', '4')->where('working_group_id', '=', '0')->get();
        $availVehicles = Vozidlo::where('working_group_id', '=', '0')->get();
        $vehicles = Vozidlo::where('working_group_id', '!=', '0')->get();
        $workingGroups = WorkingGroup::with('users')->with('vehicle')->with('assignedCategories')->with('assignedProblems')->get();
        $categories = KategoriaProblemu::all();
        $groupProblems = null;
        $problemsToAssign = null;

        return view('views.manager.manager_assignProblems')
            ->with('users', $availUsers)
            ->with('availVehicles', $availVehicles)
            ->with('vehicles', $vehicles)
            ->with('workingGroups', $workingGroups)
            ->with('categories', $categories)
            ->with('groupProblems', $groupProblems)
            ->with('problemsToAssign', $problemsToAssign);
    }

    public function manageGroupProblems($id) {
        $solStatusAssignedProblems = array();
        $solStatusProblemsToAssign = array();
        $solStatusTypes = TypStavuRieseniaProblemu::all();
        $priorities = Priorita::all();

        $groupProblems = WorkingGroup::whereHas('vehicle', function ($query) use ($id) {
                $query->where('vozidlo_id', '=', $id);
            })
            ->with('assignedCategories')
            ->with('assignedProblems')
            ->get();

        $problemsToAssign = Problem::where('working_group_id', '=', '0')
            ->where(function($query) use ($groupProblems) {
                foreach ($groupProblems[0]->assignedCategories as $assignedCategory) {
                    $query->orWhere('kategoria_problemu_id', '=', $assignedCategory->kategoria_problemu_id);
                }
            })->get();

        foreach ($problemsToAssign as $problem) {
            $typ = DB::table('stav_riesenia_problemu')
                ->where('problem_id', '=', $problem->problem_id)
                ->latest('stav_riesenia_problemu_id')->first();
            array_push($solStatusProblemsToAssign, $typ->typ_stavu_riesenia_problemu_id);
        }

        foreach ($groupProblems[0]->assignedProblems as $problem) {
            $typ = DB::table('stav_riesenia_problemu')
                ->where('problem_id', '=', $problem->problem_id)
                ->latest('stav_riesenia_problemu_id')->first();
            array_push($solStatusAssignedProblems, $typ->typ_stavu_riesenia_problemu_id);
        }

        return view('components.manager.manageGroupProblems')
            ->with('groupProblems', $groupProblems)
            ->with('problemsToAssign', $problemsToAssign)
            ->with('solStatusProblemsToAssign', $solStatusProblemsToAssign)
            ->with('solStatusAssignedProblems', $solStatusAssignedProblems)
            ->with('solStatusTypes', $solStatusTypes)
            ->with('priorities', $priorities);
    }

    public function assignProblemsToGroup(Request $request) {
        for ($i = 0; $i < count($request->problemsToAssign); $i++) {
            $problem = Problem::find($request->problemsToAssign[$i]);
            $problem->working_group_id = $request->workingGroupID;
            $problem->priorita_id = $request->prioritiesToAssign[$i];
            $problem->save();
            StavRieseniaProblemu::create(['problem_id' => $request->problemsToAssign[$i], 'typ_stavu_riesenia_problemu_id' => 3]); //v procese
        }
        return $this->manageGroupProblems($request->workingGroupID);
    }

    public function removeProblemsFromGroup(Request $request) {
        for ($i = 0; $i < count($request->problemsToAssign); $i++) {
            $problem = Problem::find($request->problemsToAssign[$i]);
            $problem->working_group_id = 0;
            $problem->priorita_id = 1;
            $problem->save();
            StavRieseniaProblemu::create(['problem_id' => $request->problemsToAssign[$i], 'typ_stavu_riesenia_problemu_id' => 5]); //odložené
        }
        return $this->manageGroupProblems($request->workingGroupID);
    }

    public function manageWorkingGroups() {
        $availUsers = User::where('rola_id', '=', '4')->where('working_group_id', '=', '0')->get();
        $availVehicles = Vozidlo::where('working_group_id', '=', '0')->get();
        $workingGroups = WorkingGroup::with('users')->with('vehicle')->with('assignedCategories')->with('assignedProblems')->get();
        $categories = KategoriaProblemu::all();

        return view('views.manager.manager_workingGroups')
            ->with('users', $availUsers)
            ->with('availVehicles', $availVehicles)
            ->with('workingGroups', $workingGroups)
            ->with('categories', $categories);
    }

    public function changeAssignedVehicle(Request $request) {
        Vozidlo::where('vozidlo_id', '=', $request->oldVehicleID)->update(['working_group_id' => '0']);
        Vozidlo::where('vozidlo_id', '=', $request->newVehicleID)->update(['working_group_id' => $request->workingGroupID]);

        return json_encode(['res'=>true]);
    }

    public function createVehicle(Request $request) {
        $request->validate([
            'spz' => ['required', 'unique:Vozidlo,SPZ', 'size:7'],
            'vehName' => 'required',
            'kmCount' => ['required', 'integer']
        ]);
        if ($request->note) {
            $note = $request->note;
        }
        else {
            $note = 'Žiadna poznámka';
        }

        Vozidlo::create([
            'oznacenie' => $request->vehName,
            'SPZ' => $request->spz,
            'pocet_najazdenych_km' =>  $request->kmCount,
            'poznamka' => $note
        ]);

        return redirect()->back()
            ->with('status', 'Vozidlo úspešne pridané do evidencie!');
    }

    public function workingGroupChart($id) {
        $groupProblems = WorkingGroup::whereHas('vehicle', function ($query) use ($id) {
            $query->where('vozidlo_id', '=', $id);
        })
            ->with('assignedProblems')
            ->get();

        $inProcessProbMonths = new SplFixedArray(12);
        $finishedProbMonths = new SplFixedArray(12);

        foreach ($groupProblems[0]->assignedProblems as $assignedProblem) {
            $state = StavRieseniaProblemu::where('problem_id', '=', $assignedProblem->problem_id)
                ->latest('stav_riesenia_problemu_id')->first();

            $index = intval(Carbon::parse($state->created_at)->format('m')) - 1;

            if ($state->typ_stavu_riesenia_problemu_id == 3) {

                $inProcessProbMonths[$index] += 1;
            }
            else if ($state->typ_stavu_riesenia_problemu_id == 4) {
                $finishedProbMonths[$index] += 1;
            }
        }

        return response()->json([
            'inProcessProbMonths' => $inProcessProbMonths,
            'finishedProbMonths' => $finishedProbMonths
        ]);
    }

    public function workingGroupUsers($id) {
        $selGroup = WorkingGroup::whereHas('vehicle', function ($query) use ($id) {
            $query->where('vozidlo_id', '=', $id);
        })
            ->with('users')
            ->with('assignedCategories')
            ->get();
        $categories = KategoriaProblemu::all();

        return view('components.manager.workingGroupUsersCat')
            ->with('selGroup', $selGroup)
            ->with('categories', $categories);
    }

    public function changeAssignedCategories(Request $request, $id) {
        $request->validate([
            'newCategories' => 'required',
        ]);

        AssignedCategoriesToGroup::where('working_group_id', '=', $id)->delete();
        foreach ($request->newCategories as $categoryId) {
            AssignedCategoriesToGroup::create(['working_group_id' => $id, 'kategoria_problemu_id' => $categoryId]);
        }

        return redirect()->back()
            ->with('status', 'Kategórie riešených problémov úspešne zmenené!');
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'selectedVehicle' => 'required',
            'selected_users' => 'required',
            'checkedCategories' => 'required',
        ]);
        WorkingGroup::create();
        $createdGroup = DB::table('working_groups')->latest('id')->first();
        Vozidlo::where('vozidlo_id', '=', $request->selectedVehicle)->update(['working_group_id' => $createdGroup->id]);
        foreach ($request->selected_users as $id) {
            User::where('id', '=', $id)->update(['working_group_id' => $createdGroup->id]);
        }

        foreach ($request->checkedCategories as $categoryId) {
            AssignedCategoriesToGroup::create(['working_group_id' => $createdGroup->id, 'kategoria_problemu_id' => $categoryId]);
        }

        return redirect()->back()
            ->with('status', 'Pracovná čata úspešne vytvorená!');
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
