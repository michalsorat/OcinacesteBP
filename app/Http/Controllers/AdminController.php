<?php

namespace App\Http\Controllers;

use App\Models\Problem;
use App\Rola;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function autocomplete(Request $request)
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
        $problemsSolvedCount = DB::table('stav_riesenia_problemu')->where('typ_stavu_riesenia_problemu_id', '4')->count();

        return view('views.admin.admin_users')
                        ->with('users', $users)
                        ->with('roles', $roles)
                        ->with('usersCount', $usersCount)
                        ->with('problemsCount', $problemsCount)
                        ->with('problemsSolvedCount', $problemsSolvedCount);
    }

    public function filter(Request $request) {
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
        }
        else {
            $users = null;
        }


        $roles = Rola::all();
        $usersCount = User::all()->count();
        $problemsCount = Problem::all()->count();
        $problemsSolvedCount = DB::table('stav_riesenia_problemu')->where('typ_stavu_riesenia_problemu_id', '4')->count();

        return view('components.admin.usersTable')
            ->with('users', $users)
            ->with('roles', $roles)
            ->with('usersCount', $usersCount)
            ->with('problemsCount', $problemsCount)
            ->with('problemsSolvedCount', $problemsSolvedCount);
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
        //
    }

    public function destroy($id)
    {

    }
}
