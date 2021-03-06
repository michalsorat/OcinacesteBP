<?php

namespace App\Http\Controllers;

use App\Models\Problem;
use App\Models\StavRieseniaProblemu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\User;
use App\Rola;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {

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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if($request->name != $user->name){
            $request->validate([
                'name' => ['required', 'min:5', 'string', 'max:255']
            ]);
        }


        if($request->email != $user->email){
            $request->validate([
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users']
            ]);
        }

        if($request->password != null){
            $request->validate([
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
        }

        $user->name = $request->name;
        $user->email = $request->email;

        if($request->password != null) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $users = User::find($id);
        $users->delete();

        return redirect()->back()
            ->with('status', '????et ??spe??ne zmazan??!');
    }

    public function getUserDetails($id) {
        $userProblems = Problem::with('StavRieseniaProblemu')->where('pouzivatel_id', '=', $id)->get();
        $createdProblemsCount = $userProblems->count();
        $solvedProblemsCount = 0;
        $acceptedProblemsCount = 0;
        $inProgressProblemsCount = 0;
        foreach ($userProblems as $problem) {
            if ($problem->StavRieseniaProblemu->typ_stavu_riesenia_problemu_id == 4) {
                $solvedProblemsCount++;
            }
            else if ($problem->StavRieseniaProblemu->typ_stavu_riesenia_problemu_id == 1) {
                $acceptedProblemsCount++;
            }
            else if ($problem->StavRieseniaProblemu->typ_stavu_riesenia_problemu_id == 3) {
                $inProgressProblemsCount++;
            }
        }

        $user = User::find($id);

        $newDateFormat = Carbon::parse($user->created_at)->format('d. m. Y, H:i:s');

        $returnArr = array('countsArr' => array(0 => $createdProblemsCount,
                                                1 => $solvedProblemsCount,
                                                2 => $acceptedProblemsCount,
                                                3 => $inProgressProblemsCount),
                            'regDate' => $newDateFormat);
        return response()->json($returnArr);
    }
}
