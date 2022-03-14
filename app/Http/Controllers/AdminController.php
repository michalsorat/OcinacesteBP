<?php

namespace App\Http\Controllers;

use App\Models\Problem;
use App\Rola;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function autocomplete(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = User::select("name")
            ->where("name", "LIKE", "%{$request->get('query')}%")
            ->get();
        $usersArr = array();
        foreach ($data as $obj) {
            array_push($usersArr, (json_decode($obj)->name));
        }
        return response()->json($usersArr);
    }

    public function index()
    {
        $users = User::all();
        $roles = Rola::all();
        $usersCount = User::all()->count();
        $problemsCount = Problem::all()->count();
        $solvedProblemsCount = DB::table('stav_riesenia_problemu')->where('typ_stavu_riesenia_problemu_id', '4')->count();

        return view('views.admin.admin_users')
                        ->with('users', $users)
                        ->with('roles', $roles)
                        ->with('usersCount', $usersCount)
                        ->with('problemsCount', $problemsCount)
                        ->with('solvedProblemsCount', $solvedProblemsCount);
    }

    public function filter(Request $request) {
        $roles = Rola::all();

        if ($request->orderBy == "2") {
            $order = 'DESC';
        }
        else {
            $order = 'ASC';
        }
        if (!empty($request->checkedRoles)) {
            $users = User::whereIn('rola_id', $request->checkedRoles)
                ->when(!empty($request->nameInput), function($query) use ($request) {
                    $query->where('name', 'LIKE', "%{$request->nameInput}%");
                })
                ->orderBy('created_at', $order)->get();
            if (!sizeof($users)) {
                $users = null;
            }
        }
        else {
            $users = null;
        }

        return view('components.admin.usersTable')
            ->with('users', $users)
            ->with('roles', $roles);
    }

    public function countProblemsOrUsers(Request $request): \Illuminate\Http\JsonResponse
    {
        if (!empty($request->problemDate)) {
            $problemsCount = Problem::where( 'created_at', '>', Carbon::now()->subDays($request->problemDate))->count();
        }
        else {
            $problemsCount = Problem::all()->count();
        }

        if (!empty($request->problemSolvedDate)) {
            $solvedProblemsCount = DB::table('stav_riesenia_problemu')
                ->where('typ_stavu_riesenia_problemu_id', '=', '4')
                ->where( 'created_at', '>', Carbon::now()->subDays($request->problemSolvedDate))
                ->count();
        }
        else {
            $solvedProblemsCount = DB::table('stav_riesenia_problemu')
                ->where('typ_stavu_riesenia_problemu_id', '=', '4')
                ->count();
        }

        if (!empty($request->userDate)) {
            $usersCount = User::where( 'created_at', '>', Carbon::now()->subDays($request->userDate))->count();
        }
        else {
            $usersCount = User::all()->count();
        }

        $returnArr = array(
            'usersCount' => $usersCount,
            'solvedProblemsCount' => $solvedProblemsCount,
            'problemsCount' => $problemsCount);

        return response()->json($returnArr);
    }

    public function userRoleInfo($id) {
        $user = User::findOrFail($id);
        $roles = Rola::all();

        return view('partials.admin.admin_editRole')
            ->with('user', $user)
            ->with('roles', $roles);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->rola_id = $request->rola_id;
        $user->save();

        return redirect()->back()
            ->with('status', 'Používateľská rola úspešne zmenená!');
    }

    public function deleteUser(Request $request) {
        $user = User::findOrFail($request->userID);
        $user->delete();

        return redirect()->back()
            ->with('status', 'Účet úspešne zmazaný!');
    }

    public function destroy($id)
    {

    }
}
