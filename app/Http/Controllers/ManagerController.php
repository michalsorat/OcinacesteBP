<?php

namespace App\Http\Controllers;

use App\Models\AssignedCategoriesToGroup;
use App\Models\KategoriaProblemu;
use App\Models\Priorita;
use App\Models\Problem;
use App\Models\ProblemHistoryRecord;
use App\Models\StavRieseniaProblemu;
use App\Models\TypStavuRieseniaProblemu;
use App\Models\Vozidlo;
use App\Models\WorkingGroup;
use App\Models\WorkingGroupHistory;
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

        $groupProblems = WorkingGroup::where('id', '=', $id)
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
//        dd($solStatusAssignedProblems);

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
            $problem = Problem::where('problem_id', '=', $request->problemsToAssign[$i])->first();
            $problem->working_group_id = $request->workingGroupID;
            $problem->priorita_id = $request->prioritiesToAssign[$i];
            $problem->save();
            $latestSolState = StavRieseniaProblemu::latest()->where('problem_id', '=', $request->problemsToAssign[$i])->first();
            StavRieseniaProblemu::create(['problem_id' => $request->problemsToAssign[$i], 'typ_stavu_riesenia_problemu_id' => 3]); //v procese
            ProblemHistoryRecord::create(['problem_id' => $request->problemsToAssign[$i], 'type' => 'Zmena stavu riešenia', 'description' => $latestSolState->TypStavuRieseniaProblemu['nazov'].' -> V procese']);
            WorkingGroupHistory::create(['working_group_id' => $request->workingGroupID, 'type' => 'Priradenie problému čate', 'description' => 'ID problému- '.$request->problemsToAssign[$i]]);
        }
        return $this->manageGroupProblems($request->workingGroupID);
    }

    public function removeProblemsFromGroup(Request $request) {
        for ($i = 0; $i < count($request->problemsToAssign); $i++) {
            $problem = Problem::where('problem_id', '=', $request->problemsToAssign[$i])->first();
            $problem->working_group_id = 0;
            $problem->priorita_id = 1;
            $problem->save();
            $latestSolState = StavRieseniaProblemu::latest()->where('problem_id', '=', $request->problemsToAssign[$i])->first();
            StavRieseniaProblemu::create(['problem_id' => $request->problemsToAssign[$i], 'typ_stavu_riesenia_problemu_id' => 5]); //odložené
            ProblemHistoryRecord::create(['problem_id' => $request->problemsToAssign[$i], 'type' => 'Zmena stavu riešenia', 'description' => $latestSolState->TypStavuRieseniaProblemu['nazov'].' -> Odložené']);
            WorkingGroupHistory::create(['working_group_id' => $request->workingGroupID, 'type' => 'Odobratie problému čate', 'description' => 'ID problému- '.$request->problemsToAssign[$i]]);

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
        $oldVeh = Vozidlo::where('vozidlo_id', '=', $request->oldVehicleID)->first();
        $newVeh = Vozidlo::where('vozidlo_id', '=', $request->newVehicleID)->first();
        $oldVeh->working_group_id = 0;
        $oldVeh->save();
        $newVeh->working_group_id = $request->workingGroupID;
        $newVeh->save();
        WorkingGroupHistory::create(['working_group_id' => $request->workingGroupID, 'type' => 'Zmena vozidla', 'description' => $oldVeh->SPZ.' -> '.$newVeh->SPZ]);

        return json_encode(['res'=>true]);
    }

    public function createVehicle(Request $request) {
        $request->validate([
            'spz' => ['required', 'unique:vozidlo,SPZ', 'size:7'],
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
        $groupProblems = WorkingGroup::where('id', '=', $id)
            ->with('assignedProblems')
            ->get();

//        $inProcessProbMonths = new SplFixedArray(12);
        $inProcessProbMonths = array(0,0,0,0,0,0,0,0,0,0,0,0);
        $finishedProbMonths = array(0,0,0,0,0,0,0,0,0,0,0,0);

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

    public function workingGroupDetail($id) {
        $selGroup = WorkingGroup::where('id', '=', $id)
            ->with('users')
            ->with('assignedCategories')
            ->with('history')
            ->get();

        $categories = KategoriaProblemu::all();
        $availUsers = User::where('rola_id', '=', '4')->where('working_group_id', '=', '0')->get();

        return view('components.manager.workingGroupDetails')
            ->with('selGroup', $selGroup)
            ->with('availUsers', $availUsers)
            ->with('categories', $categories);
    }

    public function changeAssignedCategories(Request $request, $id) {
        $request->validate([
            'newCategories' => 'required',
        ]);

        $oldAssignedCategories = AssignedCategoriesToGroup::where('working_group_id', '=', $id)->pluck('kategoria_problemu_id');
        $result = array_diff($oldAssignedCategories->toArray(), $request->newCategories);
        $result2 = array_diff($request->newCategories, $oldAssignedCategories->toArray());

        foreach ($result2 as $addCategoryID) {
            $category = KategoriaProblemu::where('kategoria_problemu_id', '=', $addCategoryID)->first();
            AssignedCategoriesToGroup::create(['working_group_id' => $id, 'kategoria_problemu_id' => $addCategoryID]);
            WorkingGroupHistory::create(['working_group_id' => $id, 'type' => 'Pridaná kategória riešených problémov', 'description' => $category->nazov]);
        }
        foreach ($result as $removeCategoryID) {
            $category = KategoriaProblemu::where('kategoria_problemu_id', '=', $removeCategoryID)->first();
            AssignedCategoriesToGroup::where('working_group_id', '=', $id)->where('kategoria_problemu_id', '=', $removeCategoryID)->delete();
            WorkingGroupHistory::create(['working_group_id' => $id, 'type' => 'Odobratá kategória riešených problémov', 'description' => $category->nazov]);
        }

        return redirect()->back()
            ->with('status', 'Kategórie riešených problémov úspešne zmenené!');
    }

    public function removeGroupUsers(Request $request, $id) {
        foreach ($request->selected_users as $userID) {
            $user = User::where('id', '=', $userID)->first();
            $user->working_group_id = 0;
            $user->save();
            WorkingGroupHistory::create(['working_group_id' => $id, 'type' => 'Odobratý pracovník čaty', 'description' => 'ID'.$user->id.' Meno-'.$user->name]);
        }
        return redirect()->back()
            ->with('status', 'Zamestnanci z pracovnej čaty úspešne odstránení!');
    }

    public function addGroupUsers(Request $request, $id) {
        foreach ($request->selected_users as $userID) {
            $user = User::where('id', '=', $userID)->first();
            $user->working_group_id = $id;
            $user->save();
            WorkingGroupHistory::create(['working_group_id' => $id, 'type' => 'Priradený pracovník čate', 'description' => 'ID-'.$user->id.', '.$user->name]);
        }
        return redirect()->back()
            ->with('status', 'Zamestnanci úspešne pridaní do pracovnej čaty!');
    }

    public function deleteWorkingGroup(Request $request){
        $workingGroup = WorkingGroup::where('id', '=', $request->workingGroupID)->first();

        $workingGroup->assignedProblems()->update(['working_group_id' => 0]);
        $workingGroup->users()->update(['working_group_id' => 0]);
        $workingGroup->vehicle()->update(['working_group_id' => 0]);
        $workingGroup->assignedCategories()->delete();
        $workingGroup->history()->delete();
        $workingGroup->delete();

        return redirect()->back()
            ->with('status', 'Pracovná čata úspešne zmazaná!');
    }

    public function deleteVehicle(Request $request) {
        Vozidlo::where('vozidlo_id', '=', $request->vehicleID)->delete();

        return redirect()->back()
            ->with('status', 'Vozidlo úspešne odstránené z evidencie!');
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
        $createdGroup = WorkingGroup::create();

        $vehicle = Vozidlo::where('vozidlo_id', '=', $request->selectedVehicle)->first();
        $vehicle->working_group_id = $createdGroup->id;
        $vehicle->save();
        WorkingGroupHistory::create(['working_group_id' => $createdGroup->id, 'type' => 'Priradené vozidlo', 'description' => 'Vozidlo '.$vehicle->SPZ]);

        foreach ($request->selected_users as $id) {
            $user = User::where('id', '=', $id)->first();
            $user->working_group_id = $createdGroup->id;
            $user->save();
            WorkingGroupHistory::create(['working_group_id' => $createdGroup->id, 'type' => 'Priradený pracovník čate', 'description' => 'ID-'.$user->id.', '.$user->name]);
        }

        foreach ($request->checkedCategories as $categoryId) {
            $category = KategoriaProblemu::where('kategoria_problemu_id', '=', $categoryId)->first();
            AssignedCategoriesToGroup::create(['working_group_id' => $createdGroup->id, 'kategoria_problemu_id' => $categoryId]);
            WorkingGroupHistory::create(['working_group_id' => $id, 'type' => 'Pridaná kategória riešených problémov', 'description' => $category->nazov]);
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
