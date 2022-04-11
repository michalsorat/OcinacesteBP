<?php

namespace App\Http\Controllers;

use App\Models\Problem;
use App\Models\StavRieseniaProblemu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MobileAppProblemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function myProblems()
    {
        $user = auth('api')->user();
        $problem = Problem::where('pouzivatel_id', $user->id)->paginate(15);
        return response()->json($problem->toArray());
    }


    public function storeBump(Request $request) {

        $user = auth('api')->user();

        $radius_meters = 10;

        $coordinates = $request->get("poloha");
        $coordinates_array = explode(',', $coordinates);
        $latitude = $coordinates_array[0];
        $longitude = $coordinates_array[1];

        // Find any problems which are closer than specified radius
        $problems = Problem::selectRaw("problem_id,
                         ( 6371000 * acos( cos( radians(?) ) *
                           cos( radians( CAST(SUBSTRING_INDEX(poloha,',',1) AS DOUBLE) ) )
                           * cos( radians( CAST(SUBSTRING_INDEX(poloha,',',-1) AS DOUBLE) ) - radians(?)
                           ) + sin( radians(?) ) *
                           sin( radians( CAST(SUBSTRING_INDEX(poloha,',',1) AS DOUBLE) ) ) )
                         ) AS distance", [$latitude, $longitude, $latitude])
            ->where('isBump', '=', 1)
            ->having("distance", "<", $radius_meters)
            ->orderBy("distance",'asc')
            ->offset(0)
            ->limit(1)
            ->get();

        if($problems->count() > 0) {
            Problem::find($problems->get(0)['problem_id'])->increment('detection_count', 1);
            return response()->json('ok');
        }

        $request->request->add(['kategoria_problemu_id' => '1']);
        $request->request->add(['stav_problemu_id' => '2']);
        $request->request->add(['popis_problemu' => 'Automaticky detekovaný výtlk na ceste']);
        $request->request->add(['isBump' => true]);
        $request->request->add(['pouzivatel_id' => $user->id]);

        Problem::create($request->all());

        $last = DB::table('problem')->latest('problem_id')->first();
        StavRieseniaProblemu::create(['problem_id' => $last->problem_id, 'typ_stavu_riesenia_problemu_id' => 1]);

        return response()->json('ok');
    }
}