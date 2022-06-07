<?php

namespace App\Http\Controllers;

use App\Models\FotkaProblemu;
use App\Models\Problem;
use App\Models\ProblemDetectedByAlgorithms;
use App\Models\StavRieseniaProblemu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\SuperclusterTrait;
use App\Traits\ProblemTrait;

class MobileAppProblemController extends Controller
{
    use SuperclusterTrait;
    use ProblemTrait;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function myProblems()
    {
        $user = auth('api')->user();
        $problem = Problem::where('pouzivatel_id', $user->id)->with('problemDetectedByAlgorithms')->paginate(15);
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
        $problems = $this->checkIfProblemExists($radius_meters, $latitude, $longitude, 1);

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

        $algorithms = $request->get('detectedByAlgorithms');
        if($algorithms) {
            foreach ($algorithms as $algorithm) {
                ProblemDetectedByAlgorithms::create(['problem_id' => $last->problem_id, 'algorithm' => $algorithm]);
            }
        }

        $this->refreshSuperclusterIndex();

        return response()->json('ok');
    }

    public function createProblem(Request $request)
    {
        $request->validate([
            'poloha' => 'required',
            'popis_problemu' => 'required',
            'uploaded_images.*' => 'mimes:jpeg,bmp,png'
        ]);

        $user = auth('api')->user();

        $request->request->add(['pouzivatel_id' => $user->id]);

        $request->request->add(['isBump' => false]);

        Problem::create($request->all());

        $last = DB::table('problem')->latest('problem_id')->first();
        StavRieseniaProblemu::create(['problem_id' => $last->problem_id, 'typ_stavu_riesenia_problemu_id' => 1]);

        if ($request->hasFile('uploaded_images')) {
            foreach ($request->file('uploaded_images') as $uploadedImage) {
                $fileName = date('Y-m-d-') . $uploadedImage->hashName();
                $uploadedImage->storeAs('problemImages', $fileName, 'public');
                FotkaProblemu::create(['problem_id' => $last->problem_id, 'nazov_suboru' => $fileName]);
            }
        }

        $this->refreshSuperclusterIndex();

        return response()->json('ok');
    }
}
