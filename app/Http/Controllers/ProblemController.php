<?php

namespace App\Http\Controllers;

use App\Models\FotkaRieseniaProblemu;
use App\Models\PopisStavuRieseniaProblemu;
use App\Models\PriradeneVozidlo;
use App\Models\PriradenyZamestnanec;
use App\Models\ProblemHistoryRecord;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Problem;
use App\Models\KategoriaProblemu;
use App\Models\StavProblemu;
use App\Models\TypStavuRieseniaProblemu;
use App\Models\StavRieseniaProblemu;
use App\Models\FotkaProblemu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Traits\SuperclusterTrait;
use App\Traits\ProblemTrait;

class ProblemController extends Controller
{
    use SuperclusterTrait;
    use ProblemTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     *
     * nacitanie problemov
     * nacitanie posledneho zaznamu daneho problemu z tabulky stavy_riesenia_problemov,
     * kde sa nachadza aktualny stav riesenia pre dany problem
     * (ostatne stavy pred poslednym su historicke stavy riesenia)
     * get atribut typ_stavu_riesenia_problemu_id
     * vlozenie do pola stavy_riesenia
     */

    public function welcomePage(Request $request)
    {
        if ($request->ajax()) {
            if (!empty($request->checkedCategories && $request->dateFrom != $request->dateTo)) {
                $problems = Problem::with('PopisyRiesenia')
                    ->whereIn('kategoria_problemu_id', $request->checkedCategories)
                    ->when(($request->isBump) != 'showAll', function($query) use ($request) {
                        $query->where('isBump', $request->isBump);
                    })
                    ->whereBetween('created_at', [$request->dateFrom, $request->dateTo])
                    ->orderBy('created_at', 'asc')
                    ->get();
                if ($problems->count() == 0) {
                    $problems = array();
                }
            }
            else {
                $problems = array();
            }
        }
        else {
            $problems = Problem::orderBy('created_at', 'asc')->get();
        }

        $typy_stavov_riesenia = TypStavuRieseniaProblemu::all();
        $kategorie = KategoriaProblemu::all();
        $stavyProblemu = StavProblemu::all();

        $stavy_riesenia = array();

        foreach ($problems as $problem) {
            $typ = DB::table('stav_riesenia_problemu')
                ->where('problem_id', '=', $problem->problem_id)
                ->latest('stav_riesenia_problemu_id')->first();
            array_push($stavy_riesenia, $typ->typ_stavu_riesenia_problemu_id);
        }

        if ($request->ajax()) {
            return view('components.map')
                ->with('problems', $problems)
                ->with('typy_stavov_riesenia', $typy_stavov_riesenia)
                ->with('stavy_riesenia', json_encode($stavy_riesenia))
                ->with('kategorie', $kategorie)
                ->with('stavy', $stavyProblemu);
        }
        else {
            return view('views.welcomePage')
                ->with('problems', $problems)
                ->with('typy_stavov_riesenia', $typy_stavov_riesenia)
                ->with('stavy_riesenia', json_encode($stavy_riesenia))
                ->with('kategorie', $kategorie)
                ->with('stavy', $stavyProblemu);
        }
    }

    public function autocomplete(Request $request)
    {
        $data = Problem::select("address")
            ->where("address", "LIKE", "%{$request->get('query')}%")
            ->get();
        $addressArr = array();
        foreach ($data as $obj) {
            array_push($addressArr, (json_decode($obj)->address));
        }
        return response()->json($addressArr);
    }

    public function allProblemsJsonPagination() {
        $problem = Problem::paginate(15);
        return response()->json($problem->toArray());
    }

    public function allProblemsJson() {
        $problem = Problem::all('problem_id', 'poloha', 'kategoria_problemu_id');
        return response()->json($problem);
    }

    public function allProblemsClustered(Request $request) {
        $client = new \GuzzleHttp\Client();
        $response = $client->get('http://127.0.0.1:8080/problems', [
            'query' => [
                'zoom' => $request->query('zoom'),
                'westLng' => $request->query('westLng'),
                'southLat' => $request->query('southLat'),
                'eastLng' => $request->query('eastLng'),
                'northLat' => $request->query('northLat')
            ]
        ]);
        return response()->json(json_decode($response->getBody()));
    }

    public function problemById($id) {
        $problem = Problem::with('problemImage')->where('problem_id', $id)->first();
        return response()->json($problem);
    }

    public function image($id)
    {
        $problem = Problem::with('problemImage')->with('problemSolImage')->where('problem_id', $id)->firstOrFail();
        return view('partials.problemImage')
            ->with('problem', $problem);
    }

    private function getPaginator(Request $request, $items)
    {
        $total = count($items); // total count of the set, this is necessary so the paginator will know the total pages to display
        $page = $request->page ?? 1; // get current page from the request, first page is null
        $perPage = 10; // how many items you want to display per page?
        $offset = ($page - 1) * $perPage; // get the offset, how many items need to be "skipped" on this page
        $items = array_slice($items, $offset, $perPage); // the array that we actually pass to the paginator is sliced

        return new LengthAwarePaginator($items, $total, $perPage, $page, [
            'path' => $request->url(),
            'query' => $request->query()
        ]);
    }

    public function index(Request $request)
    {
        if ($request->orderBy == "1") {
            $finalTmp = Problem::orderBy('created_at', 'DESC')->get();
        }
        else {
            $finalTmp = Problem::orderBy('created_at', 'ASC')->get();
        }

        $final = array();
        foreach ($finalTmp as $problem) {
            if ($request->myProblems != null && Auth::user() != null) {
                if ($problem->pouzivatel_id != Auth::user()->id) {
                    continue;
                }
            }
            if ($request->dontShowBumps != null) {
                if ($problem->isBump == 1) {
                    continue;
                }
            }
            if ($request->kategoria_problemu_id != null) {
                if ($problem->kategoria_problemu_id != $request->kategoria_problemu_id) {
                    continue;
                }
            }
            if ($request->stav_problemu_id != null) {
                if ($problem->stav_problemu_id != $request->stav_problemu_id) {
                    continue;
                }
            }
            if ($request->typ_stavu_riesenia_problemu_id != null) {
                $stav_riesenia = DB::table('stav_riesenia_problemu')
                    ->where('problem_id', '=', $problem->problem_id)
                    ->latest('stav_riesenia_problemu_id')->first();

                if ($request->typ_stavu_riesenia_problemu_id != $stav_riesenia->typ_stavu_riesenia_problemu_id) {
                    continue;
                }
            }
            if ($request->lattitude != null && $request->longitude != null & $request->radius != null) {
                $R = 3958.8;
                $latitudeFrom = $request->lattitude * (M_PI / 180);
                $longitudeFrom = $request->longitude;
                $lanLonString = $problem->poloha;
                $lanLonArray = explode(',', $lanLonString);
                $latitudeTo = $lanLonArray[0] * (M_PI / 180);
                $longitudeTo = $lanLonArray[1];

                $diffLat = $latitudeTo - $latitudeFrom;
                $diffLon = ($longitudeTo - $longitudeFrom) * (M_PI / 180);
                $dist = (2 * $R * asin(sqrt(sin($diffLat / 2) * sin($diffLat / 2) + cos($latitudeFrom) * cos($latitudeTo) * sin($diffLon / 2) * sin($diffLon / 2)))) * 1.609344;
                if ($dist > $request->radius) {
                    continue;
                }
            }
            array_push($final, $problem);
        }

        $typy_stavov = TypStavuRieseniaProblemu::all();
        $stavy_riesenia = array();
        $kategorie = KategoriaProblemu::all();
        $stavyProblemu = StavProblemu::all();
        $typyStavovRieseniaProblemu = TypStavuRieseniaProblemu::all();

        $paginator = $this->getPaginator($request, $final);

        foreach ($paginator as $problem) {
            $typ = DB::table('stav_riesenia_problemu')
                ->where('problem_id', '=', $problem->problem_id)
                ->latest('stav_riesenia_problemu_id')->first();
            array_push($stavy_riesenia, $typ->typ_stavu_riesenia_problemu_id);
        }

        if ($request->ajax()){
            return view('components.problemsTable')
                ->with('problems', $paginator)
                ->with('stavy_riesenia', $stavy_riesenia)
                ->with('typy_stavov_riesenia', $typy_stavov)
                ->with('stavyProblemu', $stavyProblemu);
        }
        else {
            return view('views.allProblemsTable')
                ->with('problems', $paginator)
                ->with('stavy_riesenia', $stavy_riesenia)
                ->with('typy_stavov_riesenia', $typy_stavov)
                ->with('kategorie', $kategorie)
                ->with('stavyProblemu', $stavyProblemu)
                ->with('typyStavovRieseniaProblemu', $typyStavovRieseniaProblemu);
        }
    }

    public function findProblemsInRadius($radius_meters, $latitude, $longitude, $isBump) {
        // Find any problems which are closer than specified radius
        return Problem::selectRaw("problem_id, kategoria_problemu_id,
                         ( 6371000 * acos( cos( radians(?) ) *
                           cos( radians( CAST(SUBSTRING_INDEX(poloha,',',1) AS DOUBLE) ) )
                           * cos( radians( CAST(SUBSTRING_INDEX(poloha,',',-1) AS DOUBLE) ) - radians(?)
                           ) + sin( radians(?) ) *
                           sin( radians( CAST(SUBSTRING_INDEX(poloha,',',1) AS DOUBLE) ) ) )
                         ) AS distance", [$latitude, $longitude, $latitude])
            ->where('isBump', '=', $isBump)
            ->having("distance", "<", $radius_meters)
            ->orderBy("distance",'asc')
            ->offset(0)
            ->limit(1)
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     *
     * validacia povinnych polí
     * pridanie user id tvorcu problemu do requestu
     * vytvorenie zaznamu problemu v DB
     * pre kazdy vytvoreny problem, vytvorim zaznam v stav_riesenia_problemu s hodnotou prijate
     */
    public function createProblem(Request $request)
    {
        $request->validate([
            'poloha' => 'required',
            'popis_problemu' => 'required',
            'uploaded_images.*' => 'mimes:jpeg,bmp,png'
        ]);

        $radius_meters = 10;

        $coordinates = $request->get("poloha");
        $coordinates_array = explode(',', $coordinates);
        $latitude = $coordinates_array[0];
        $longitude = $coordinates_array[1];

        $problemsInRadius = $this->findProblemsInRadius($radius_meters, $latitude, $longitude, 0);
        if($problemsInRadius->count() > 0 && $problemsInRadius[0]->kategoria_problemu_id == $request->kategoria_problemu_id) {
            throw ValidationException::withMessages(['exists' => 'Tento problém už bol zaznačený!']);
        }

        if (Auth::user() != null) {
            $request->request->add(['pouzivatel_id' => Auth::user()->id]);
        }
        else {
            $request->request->add(['pouzivatel_id' => '1']);
        }

        $request->request->add(['isBump' => false]);

        Problem::create($request->all());

        $last = DB::table('problem')->latest('problem_id')->first();
        StavRieseniaProblemu::create(['problem_id' => $last->problem_id, 'typ_stavu_riesenia_problemu_id' => 1]);

        $category = KategoriaProblemu::where('kategoria_problemu_id', '=', $request->kategoria_problemu_id)->first();
        $problemState = StavProblemu::where('stav_problemu_id', '=', $request->stav_problemu_id)->first();
        ProblemHistoryRecord::create(['problem_id' => $last->problem_id, 'type' => 'Priradená kategória', 'description' => $category->nazov]);
        ProblemHistoryRecord::create(['problem_id' => $last->problem_id, 'type' => 'Priradený stav', 'description' => $problemState->nazov]);
        ProblemHistoryRecord::create(['problem_id' => $last->problem_id, 'type' => 'Priradený stav riešenia', 'description' => 'Prijaté']);

        if ($request->hasFile('uploaded_images')) {
            foreach ($request->file('uploaded_images') as $uploadedImage) {
                $fileName = date('Y-m-d-') . $uploadedImage->hashName();
                $uploadedImage->storeAs('problemImages', $fileName, 'public');
                FotkaProblemu::create(['problem_id' => $last->problem_id, 'nazov_suboru' => $fileName]);
            }
        }

        $this->refreshSuperclusterIndex();

        return redirect('/')
            ->with('status', 'Hlasenie bolo úspešne prijaté!');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show(Problem $problem)
    {
        $problemFull = Problem::with('problemImage')->with('problemSolImage')->with('problemHistory')->where('problem_id', '=', $problem->problem_id)->firstOrFail();
        $categories = KategoriaProblemu::all();
        $problemStates = StavProblemu::all();
        $typ = StavRieseniaProblemu::where('problem_id', '=', $problem->problem_id)
            ->latest('stav_riesenia_problemu_id')->first();
        $popis_riesenia = PopisStavuRieseniaProblemu::where('problem_id', '=', $problem->problem_id)
            ->latest('popis_stavu_riesenia_problemu_id')->first();

        return view('views.problemDetail')
            ->with('problem', $problemFull)
            ->with('stav_riesenia_problemu', $typ)
            ->with('popis_stavu_riesenia', $popis_riesenia)
            ->with('problemStates', $problemStates)
            ->with('categories', $categories);
    }

    public function edit(Problem $problem)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Problem $problem
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Problem $problem)
    {
        $request->validate([
            'file' => 'mimes:jpeg,bmp,png'
        ]);
        if ($request->hasFile('file')) {
            $uploadedImage = $request->file('file');
            $fileName = date('Y-m-d-') . $uploadedImage->hashName();
            $uploadedImage->storeAs('problemImages', $fileName, 'public');
            FotkaProblemu::create(['problem_id' => $problem->problem_id, 'nazov_suboru' => $fileName]);
            return response()->json(['success'=>$fileName]);
        }

        if ($request->newCategoryId != $problem->kategoria_problemu_id) {
            $newCategory = KategoriaProblemu::where('kategoria_problemu_id', '=', $request->newCategoryId)->first();
            ProblemHistoryRecord::create(['problem_id' => $problem->problem_id, 'type' => 'Zmena kategórie', 'description' => $problem->KategoriaProblemu['nazov'].' -> '.$newCategory->nazov]);
            $problem->kategoria_problemu_id = $request->newCategoryId;
        }
        if ($request->newStateId != $problem->stav_problemu_id) {
            $newState = StavProblemu::where('stav_problemu_id', '=', $request->newStateId)->first();
            ProblemHistoryRecord::create(['problem_id' => $problem->problem_id, 'type' => 'Zmena stavu', 'description' => $problem->StavProblemu['nazov'].' -> '.$newState->nazov]);
            $problem->stav_problemu_id = $request->newStateId;
        }
        if ($request->problemDesc != $problem->popis_problemu) {
            $problem->popis_problemu = $request->problemDesc;
            ProblemHistoryRecord::create(['problem_id' => $problem->problem_id, 'type' => 'Zmena popisu', 'description' => '']);
        }

        $problem->save();

        return redirect()->back()
            ->with('status', 'Problem úspešne aktualizovaný!');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Problem $problem
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function destroy(Problem $problem)
    {
        $problem = Problem::findOrFail($problem->problem_id);
        $problem->delete();

        return redirect('problem')
            ->with('status', 'Problem úspešne zmazaný!');
    }

    public function deleteImage(FotkaProblemu $image) {
        unlink(public_path().'/storage/problemImages/'.$image->nazov_suboru);
        Storage::delete($image->nazov_suboru);
        $image->delete();

        return redirect()->back()
            ->with('status', 'Fotka úspešne zmazaná!');
    }

    public function deleteSolImage(FotkaRieseniaProblemu $image) {
        unlink(public_path().'/storage/problemImages/'.$image->nazov_suboru);
        Storage::delete($image->nazov_suboru);
        $image->delete();

        return redirect()->back()
            ->with('status', 'Fotka úspešne zmazaná!');
    }

    //PRIDAL SIMON DRIENIK, ZOBRAZENIE VSETKYCH MARKEROV V ANDROID APPKE
    public function showAllProblemsAndroid($x, $zamestnanec, $stavProblemu, $kategoria, $datumOd, $datumDo, $vozidlo, $priorita1, $stavRiesenia, $role)
    {
            $problems = Problem::all();
            $arr = array();
        $poc = 0;

            foreach($problems as $problem)
	    {

	    $stav_problemu = DB::table('stav_problemu')
		->orderBy('created_at', 'desc')
                ->where('stav_problemu_id', '=', $problem->stav_problemu_id)
                ->pluck('nazov')
                ->first();
            $stav_problemu = iconv('UTF-8', 'UTF-8', $stav_problemu);

            $kategoria_problemu = DB::table('kategoria_problemu')
		->orderBy('created_at', 'desc')
		->where('kategoria_problemu_id', '=', $problem->kategoria_problemu_id)
                ->pluck('nazov')
                ->first();
            $kategoria_problemu = iconv('UTF-8', 'UTF-8', $kategoria_problemu);

	    $stav_riesenia_problemu = DB::table('stav_riesenia_problemu')
		->orderBy('created_at', 'desc')
                ->where('problem_id', '=', $problem->problem_id)
                ->pluck('typ_stavu_riesenia_problemu_id')
                ->first();
            $typ_stavu_riesenia_problemu = DB::table('typ_stavu_riesenia_problemu')
		->orderBy('created_at', 'desc')
		->where('typ_stavu_riesenia_problemu_id', '=', $stav_riesenia_problemu)
                ->pluck('nazov')
                ->first();
            $typ_stavu_riesenia_problemu = iconv('UTF-8', 'UTF-8', $typ_stavu_riesenia_problemu);

            $popis_stavu_riesenia_problemu = DB::table('popis_stavu_riesenia_problemu')
		->orderBy('created_at', 'desc')
		->where('problem_id', '=', $problem->problem_id)
                ->pluck('popis')
		->first();
	    $popis_stavu_riesenia_problemu_verejne = DB::table('popis_stavu_riesenia_problemu')
		    ->orderBy('created_at', 'desc')
		    ->where('problem_id', '=', $problem->problem_id)
		    ->pluck('verejne')
		    ->first();

	    $pouzivatel_meno = DB::table('users')
		    ->orderBy('created_at', 'desc')
		    ->where('id', '=', $problem->pouzivatel_id)
		    ->pluck('name')
		    ->first();

	    $priradeny_zamestnanec_id = DB::table('priradeny_zamestnanec')
		    ->orderBy('created_at', 'desc')
		    ->where('problem_id', '=', $problem->problem_id)
		    ->pluck('zamestnanec_id')
		    ->first();

	    if ($priradeny_zamestnanec_id != null)
	    {
		    $priradeny_zamestnanec_meno = DB::table('users')
			    ->orderBy('created_at', 'desc')
			    ->where('id','=', $priradeny_zamestnanec_id)
			    ->pluck('name')
			    ->first();
		    $priradeny_zamestnanec_meno = iconv('UTF-8', 'UTF-8', $priradeny_zamestnanec_meno);
	    }
	    else{
		    $priradeny_zamestnanec_meno = 'Nepriradeny';
	    }

	    $priorita = DB::table('priorita')
		    ->orderBy('created_at', 'desc')
		    ->where('priorita_id','=', $problem->priorita_id)
		    ->pluck('priorita')
		    ->first();
	    $priorita = iconv('UTF-8', 'UTF-8', $priorita);

	    $priradene_vozidlo_id = DB::table('priradene_vozidlo')
		    ->orderBy('created_at', 'desc')
		    ->where('problem_id', '=', $problem->problem_id)
		    ->pluck('vozidlo_id')
		    ->first();

	    if ($priradene_vozidlo_id != null){
		    $priradene_vozidlo_spz = DB::table('vozidlo')
			    ->orderBy('created_at', 'desc')
			    ->where('vozidlo_id', '=', $priradene_vozidlo_id)
			    ->pluck('SPZ')
			    ->first();

	    }
	    else{
		$priradene_vozidlo_spz = 'Nepriradene';
	    }

            if ($popis_stavu_riesenia_problemu != null)
	    {
		if ($popis_stavu_riesenia_problemu_verejne == 1 || $role > 2)
			$popis_stavu_riesenia_problemu = iconv('UTF-8', 'UTF-8', $popis_stavu_riesenia_problemu);
		else
			$popis_stavu_riesenia_problemu = 'neuvedene';
            }
            if ($popis_stavu_riesenia_problemu == null)
            {
             $popis_stavu_riesenia_problemu = 'neuvedene';
	    }



	    $created_at1 = $problem->created_at->toDateTimeString();

	    if (($x != 0 && $x == $problem->problem_id) || ($x == 0))
	    {
		    if (($zamestnanec == $priradeny_zamestnanec_meno) || ($zamestnanec == "-"))
			    if (($stavProblemu == $stav_problemu) || ($stavProblemu == "-"))
				    if (($kategoria == $kategoria_problemu) || ($kategoria == "-"))
					    if (strtotime($datumOd) < strtotime($created_at1) || $datumOd == "0000-00-00")
						    if (strtotime($datumDo) > strtotime($created_at1) || $datumDo == "0000-00-00")
							    if ($vozidlo == $priradene_vozidlo_spz || $vozidlo == "-")
								    if ($priorita1 == $priorita || $priorita1 == "-")
									    if ($stavRiesenia == $typ_stavu_riesenia_problemu || $stavRiesenia == "-")
										    {


                								$arr[$poc] = array(
                    								"position" => $problem->poloha,
                    								"id" => $problem->problem_id,
                    								"kategoria" => $kategoria_problemu,
                    								"popis" => $problem->popis_problemu,
                    								"stav_problemu" =>$stav_problemu,
                    								"stav_riesenia_problemu" =>$typ_stavu_riesenia_problemu,
            	    								"popis_riesenia_problemu" =>$popis_stavu_riesenia_problemu,
             	    								"created_at" => $created_at1,
		    								"pouzivatel" => $problem->pouzivatel_id,
		   	 							"priradeny_zamestnanec" => $priradeny_zamestnanec_meno,
		    								"priorita" => $priorita,
		    								"priradene_vozidlo" => $priradene_vozidlo_spz,
		    								"pouzivatel_meno" => $pouzivatel_meno
            									);

										$poc++;
									    }
	    }

       }

       return json_encode($arr);


    }

    public function getImgsAndroid($id)
    {
	$fotka_problemu = FotkaProblemu::all();
	$fotka_stavu_riesenia_problemu = FotkaStavuRieseniaProblemu::all();
	$popis_stavu_riesenia = PopisStavuRieseniaProblemu::all();
	$url_foto_problemu = "n";
	$url_foto_riesenia = "n";
	$popis_stavu_riesenia_problemu_id = 0;


	foreach($fotka_problemu as $foto_problemu)
	{
		if ($foto_problemu->problem_id == $id)
			$url_foto_problemu = $foto_problemu->cesta_k_suboru;
	}
	foreach($popis_stavu_riesenia as $popis)
	{
		if ($popis->problem_id == $id)
			$popis_stavu_riesenia_problemu_id = $popis->popis_stavu_riesenia_problemu_id;
	}

	if ($popis_stavu_riesenia_problemu_id != 0)
		foreach($fotka_stavu_riesenia_problemu as $fotka_riesenia)
		{
			if ($fotka_riesenia->popis_stavu_riesenia_id == $popis_stavu_riesenia_problemu_id)
				$url_foto_riesenia = $fotka_riesenia->cesta_k_suboru;
		}

	$arr[0] = array(
		"URLproblem" => $url_foto_problemu,
	        "URLriesenie" => $url_foto_riesenia
	);
	return json_encode($arr);
    }

    public function deleteProblem(Request $request)
    {
	$token = $request->authToken;
	$problemID = $request->problemID;

	$autheticated = 0;
	$users = User::all();

	foreach($users as $user)
	{
		if (($user->remember_token == $token and $user->rola_id != 1) and $user->rola_id != 2)
			$autheticated = 1;
	}

	if ($autheticated == 1)
	{
		$fotka_problemu = FotkaProblemu::all();
		$fotka_problemu_link = "";

		foreach($fotka_problemu as $foto)
		{
			if ($foto->problem_id == $problemID)
			{
				$fotka_problemu_link = $foto->cesta_k_suboru;
				$id_foto = $foto->fotka_problemu_id;
				DB::table('fotka_problemu')
					->where('fotka_problemu_id', '=', $id_foto)
					->delete();
				Storage::delete('/public' . $fotka_problemu_link);

			}
		}

		$stav_riesenia = StavRieseniaProblemu::all();

		foreach($stav_riesenia as $stav)
		{
			if ($stav->problem_id == $problemID)
			{
				DB::table('stav_riesenia_problemu')
					->where('stav_riesenia_problemu_id', '=', $stav->stav_riesenia_problemu_id)->delete();
			}
		}

		$popis_stavu_riesenia = PopisStavuRieseniaProblemu::all();

		foreach ($popis_stavu_riesenia as $popis)
		{
			if ($popis->problem_id == $problemID)
			{
				$foto_popisu = FotkaStavuRieseniaProblemu::all();
				foreach ($foto_popisu as $foto)
				{
					if ($foto->popis_stavu_riesenia_problemu_id == $popis->popis_stavu_riesenia_problemu_id)
					{
						$foto_link = $foto->cesta_k_suboru;
						$id_foto = $foto->fotka_stavu_riesenia_problemu_id;
						DB::table('fotka_stavu_riesenia_problemu')
							->where('fotka_stavu_riesenia_problemu_id', '=', $id_foto)
							->delete();
						Storage::delete('/public' . $foto_link);
					}
				}
				DB::table('popis_stavu_riesenia_problemu')
					->where('popis_stavu_riesenia_problemu_id', '=', $popis->popis_stavu_riesenia_problemu_id)
					->delete();
			}
		}

		$komentar = Komentar::all();

		foreach ($komentar as $koment)
		{
			if ($koment->problem_id == $problemID)
				DB::table('komentar')
				->where('komentar_id', '=', $koment->komentar_id)
				->delete();
		}

		$priradene_vozidlo = PriradeneVozidlo::all();

		foreach ($priradene_vozidlo as $vozidlo)
		{
			if ($vozidlo->problem_id == $problemID)
				DB::table('priradene_vozidlo')
				->where('priradene_vozidlo_id', '=', $vozidlo->priradene_vozidlo_id)
				->delete();
		}

		$priradeny_zamestnanec = PriradenyZamestnanec::all();

		foreach ($priradeny_zamestnanec as $zamestnanec)
		{
			if ($zamestnanec->problem_id == $problemID)
				DB::table('priradeny_zamestnanec')
				->where('priradeny_zamestnanec_id', '=', $zamestnanec->priradeny_zamestnanec_id)
				->delete();
		}

		$problem = Problem::all();

		foreach($problem as $pr)
		{
			if ($pr->problem_id == $problemID)
				DB::table('problem')
				->where('problem_id', '=', $problemID)
				->delete();
		}

		return 1;

	}
	else{
		return 0;
	}
    }

    public function getSpinnersAndroid(){

	 $popis_stavu_riesenia = TypStavuRieseniaProblemu::all();
		 $arr_popis_stavu_riesenia = array();
	 foreach($popis_stavu_riesenia as $popis_stavu)
	 {
		array_push($arr_popis_stavu_riesenia, iconv('UTF-8', 'UTF-8', $popis_stavu->nazov));
	 }

	 $users = User::all();
	 $arr_zamestnanci = array();
	 foreach($users as $user)
	 {
		 if($user->rola_id == 4)
			 array_push($arr_zamestnanci, iconv('UTF-8', 'UTF-8', $user->name));
	 }

	 $priority = Priorita::all();
	 $arr_priority = array();
	 foreach($priority as $priorita)
	 {
		array_push($arr_priority, iconv('UTF-8', 'UTF-8', $priorita->priorita));
	 }

	 $kategorie = KategoriaProblemu::all();
	 $arr_kategorie_problemu = array();
	 foreach($kategorie as $kategoria)
	 {
		array_push($arr_kategorie_problemu, iconv('UTF-8', 'UTF-8', $kategoria->nazov));
		 }

	 $stavy_problemu = StavProblemu::all();
	 $arr_stavy_problemu = array();
	 foreach($stavy_problemu as $stav)
	 {
		array_push($arr_stavy_problemu, iconv('UTF-8', 'UTF-8', $stav->nazov));
	 }

	 $vozidla = Vozidlo::all();
	 $arr_vozidla = array();
	 foreach($vozidla as $vozidlo)
	 {
		array_push($arr_vozidla, iconv('UTF-8', 'UTF-8', $vozidlo->SPZ));
	 }

	 array_push($arr_vozidla, 'Nepriradene');
	 array_push($arr_zamestnanci, 'Nepriradeny');

	 $arr[0] = array(
		 "zamestnanci" => $arr_zamestnanci,
		 "priority" => $arr_priority,
		 "kategorie" => $arr_kategorie_problemu,
		 "stavy_problemu" => $arr_stavy_problemu,
		 "stavy_riesenia" => $arr_popis_stavu_riesenia,
		 "vozidla" => $arr_vozidla );

	return json_encode($arr);

    }

    public function showUsersAndroid(){
	$users = User::all();
	$i = 0;
	foreach ($users as $user)
	{
		if ($user->rola_id != 2)
		{
			$arr[$i] = array(
				"name" => iconv('UTF-8', 'UTF-8', $user->name),
				"email" => $user->email,
				"created_at" => $user->created_at->toDateTimeString(),
				"role" => $user->rola_id
			);
			$i++;
		}
	}
	return json_encode($arr);

    }

    public function deleteAccountAndroid(Request $request)
    {
	    $authenticated = 0;
	    $users = User::all();
	    $notAdmin = 0;
	    $elseAdmin = 0;
	    $isHere = 0;
	    $anotherAdmin = 0;


	    foreach ($users as $user)
	    {
		    if ($user->rola_id == 3 and $user->remember_token == $request->AuthToken)
			    $authenticated = 1;
		    if ($user->email == $request->email and $user->rola_id != 3)
			    $notAdmin = 1;
		    if ($user->email == $request->email)
			    $isHere = 1;
		    if ($user->email != $request->email and $user->rola_id == 3)
			    $elseAdmin = 1;
		    if (($user->email == $request->email and $user->rola_id == 3) and $user->remember_token != $request->AuthToken)
			    $anotherAdmin = 1;
	    }

	    if ($authenticated == 1)
	    {
		    if ($isHere == 1)
		    {

			    if ($notAdmin == 1)
			    {
				    User::where('email', $request->email)->delete();
				    return 1;
			    }
			    else
			    {
				if ($anotherAdmin == 0)
				{
					if ($elseAdmin == 1)
					{
						User::where('email', $request->email)->delete();
						return 1;
					}
					else
						return 2;
				}
				else
					return 3;
			    }

		    }
		    else
			    return 0;

	    }
	    else
		    return -1;
    }

    public function editAccountAndroid(Request $request)
    {
	$authenticated = 0;
	$users = User::all();
	$isHere = 0;
	$isAdmin = 0;
	$anotherAdmin = 0;
	$elseAdmin = 0;

	foreach ($users as $user)
	{
		if ($user->rola_id == 3 and $user->remember_token == $request->AuthToken)
			$authenticated = 1;
		if ($user->email == $request->email)
			$isHere = 1;
		if ($user->email == $request->email and $user->rola_id == 3)
			$isAdmin = 1;
		if (($user->email == $request->email and $user->rola_id == 3) and $user->remember_token != $request->AuthToken)
			$elseAdmin = 1;
		if ($user->email != $request->email and $user->rola_id == 3)
			$anotherAdmin = 1;
	}

	if ($authenticated == 1)
	{
		if ($isHere == 1)
		{
			if ($isAdmin == 1)
			{
				if ($elseAdmin == 0)
				{
					if ($anotherAdmin == 1)
					{
					$user1 = User::where('email', $request->email)->first();
					$user1->rola_id = $request->rola;
					$user1->save();
					return 1;
					}
					else
						return 3;
				}
				else
					return 2;

			}
			else
			{
				$user1 = User::where('email', $request->email)->first();
				$user1->rola_id = $request->rola;
				$user1->save();
				return 1;
			}
		}
		else
			return 0;

	}
	else
		return -1;


    }



    public function unregisteredAddRecordAndroid($poloha, $popis_problemu, $kategoria_problemu, $stav_problemu, $imgId, $idOfUser)
    {
        $arr = array();


            if ($poloha != null)
        {
        $kategoria_problemu_id = DB::table('kategoria_problemu')
            ->where('nazov', $kategoria_problemu)
            ->pluck('kategoria_problemu_id')
            ->first();

        $stav_problemu_id = DB::table('stav_problemu')
            ->where('nazov', $stav_problemu)
            ->pluck('stav_problemu_id')
            ->first();

    $arr = array(
        'pouzivatel_id' => $idOfUser,
            'poloha' => $poloha,
        'popis_problemu' => $popis_problemu,
        'kategoria_problemu_id' => $kategoria_problemu_id,
        'stav_problemu_id' => $stav_problemu_id
	);



    	Problem::create($arr);
	$last = DB::table('problem')->latest('problem_id')->first();
	StavRieseniaProblemu::create(['problem_id' => $last->problem_id, 'typ_stavu_riesenia_problemu_id' => 1]);

	if ($imgId != 0){
		DB::table('fotka_problemu')
			->where('fotka_problemu_id', $imgId)
			->update(['problem_id' => $last->problem_id]);

	}

	//return 1;





	}

	return 1;


    }

    public function storeProblemImgAndroid(Request $request){

	/*$validated = $request->validate([
			'name' => 'string|max:40',
			'image'=> 'mimes:jpeg,png|max:100070'
		]);*/


			$extension = $request->file('image')->extension();
			$request->image->storeAs('/public', $request->image.".".$extension);
			$url = Storage::url($validated['name'].".".$extension);
			$arr = array('cesta_k_suboru' => $url, 'problem_id' => 1);
			FotkaProblemu::create($arr);

			$last = DB::table('fotka_problemu')->latest('fotka_problemu_id')->first();

			//return $last->fotka_problemu_id;
			return $extension;

    }

    public function historyAndroid(Request $request){

	$attribute = $request->attribute;
	$problemID = $request->problemID;
	$arr = array();

	if ($attribute == 0)
	{
		$items = FotkaStavuRieseniaProblemu::all();
	}
	if ($attribute == 1)
	{
		$items = Komentar::all();
	}
	if ($attribute == 2)
	{
		$items = PopisStavuRieseniaProblemu::all();
	}
	if ($attribute == 3)
	{
		$items = PriradeneVozidlo::all();
	}
	if ($attribute == 4)
	{
		$items = PriradenyZamestnanec::all();
	}
	if ($attribute == 5)
	{
		$items = StavRieseniaProblemu::all();
	}

	$i = 0;
	$pocet = 0;
	$date = "";
	$name = "";

	if ($attribute == 0)
	{
		$popisy = PopisStavuRieseniaProblemu::all();
		foreach ($popisy as $popis)
		{
			if ($popis->problem_id == $problemID)
			{
				foreach ($items as $item)
				{
					if ($item->popis_stavu_riesenia_id == $popis->popis_stavu_riesenia_problemu_id)
					{
						$arr[$pocet] = array(
							'meno' => $item->cesta_k_suboru,
							'date' => $item->created_at->toDateTimeString(),
							'user' => "none"
						);
						$pocet++;
					}
				}
			}

		}

	}

	if ($attribute == 1)
	{
		foreach ($items as $item)
		{
			if ($item->problem_id == $problemID)
			{
				$text = $item->komentar;
				$datum = $item->created_at->toDateTimeString();
				$users = User::all();
				foreach ($users as $user)
				{
					if ($user->id == $item->pouzivatel_id)
						$uzivatel = $user->name;
				}
				$arr[$pocet] = array(
					'meno' => $text,
					'date' => $datum,
					'user' => $uzivatel
				);
				$pocet++;
			}
		}
	}

	if ($attribute == 2)
	{
		foreach ($items as $item)
		{
			if ($item->problem_id == $problemID && ($item->verejne == 1 || $request->role  > 2))
			{
				$arr[$pocet] = array(
					'meno' => $item->popis,
					'date' => $item->created_at->toDateTimeString(),
					'user' => "none"
				);
				$pocet++;
			}
		}
	}

	if ($attribute == 3)
	{
		foreach ($items as $item)
		{
			if ($item->problem_id == $problemID)
			{
				$date = $item->created_at->toDateTimeString();
				$vozidla = Vozidlo::all();
				foreach ($vozidla as $vozidlo)
				{
					if ($vozidlo->vozidlo_id == $item->vozidlo_id)
					{
						$meno = $vozidlo->SPZ;
					}
				}
				$arr[$pocet] = array(
					'meno' => $meno,
					'date' => $date,
					'user' => "none"
				);
				$pocet++;
			}
		}
	}

	if ($attribute == 4)
	{
		foreach ($items as $item)
		{
			if ($item->problem_id == $problemID)
			{
				$users = User::all();
				foreach ($users as $user)
				{
					if ($user->id == $item->zamestnanec_id)
						$name = $user->name;
				}
				$arr[$pocet] = array(
					'meno' => $name,
					'date' => $item->created_at->toDateTimeString(),
					'user' => "none"
				);
				$pocet++;

			}
		}
	}

	if ($attribute == 5)
	{
		foreach ($items as $item)
		{
			if ($item->problem_id == $problemID)
			{
				$typy = TypStavuRieseniaProblemu::all();
				foreach ($typy as $typ)
				{
					if ($typ->typ_stavu_riesenia_problemu_id == $item->typ_stavu_riesenia_problemu_id)
						$meno = $typ->nazov;
				}
				$arr[$pocet] = array(
					'meno' => $meno,
					'date' => $item->created_at->toDateTimeString(),
					'user' => "none"
				);
				$pocet++;
			}
		}
	}

	return json_encode($arr);

    }

    public function uploadRiesenieImage(Request $request)
    {
	$users = User::all();
	$token = $request->token;
	$riesenieID = (int)$request->riesenieID;
	$autheticated = 0;

	foreach($users as $user)
	{
		if ($user->remember_token == $token and ($user->rola_id == 3 || $user->rola_id == 4 || $user->rola_id == 5))
			$autheticated = 1;
	}

	if ($autheticated == 1)
	{
		$request->image->storeAs('/public', $request->name.".".$request->image->extension());
		$url = Storage::url($request->name.".".$request->image->extension());
		$arr = array('cesta_k_suboru' => $url, 'popis_stavu_riesenia_id' => $riesenieID);
		FotkaStavuRieseniaProblemu::create($arr);
		return 1;


	}
	else
	{
		return 0;
	}


    }

    public function comment(Request $request)
    {
	$users = User::all();
	$token = $request->token;
	$problemID = $request->idProblem;
	$komentText = $request->komentText;
	$userID = $request->userID;
	$autheticated = 0;

	if ($userID > 1)
	{
		foreach ($users as $user)
		{
			if ($user->remember_token == $token && $user->id == $userID)
				$autheticated = 1;
		}
	}
	else
	{
		$autheticated = 1;
	}

	if ($autheticated == 1)
	{
		Komentar::create(['je_zamestnanec' => 0, 'komentar' => $komentText, 'problem_id' => $problemID, 'pouzivatel_id' => $userID, 'zverejnitelnost' => 1]);
		return 1;
	}
	else{
		return 5;
	}
    }


    public function editProblem(Request $request)
    {
	    $users = User::all();
	    $token = $request->token;
	    $autheticated = 0;

	    foreach($users as $user)
	    {
		if ($user->remember_token == $token and ($user->rola_id == 3 || $user->rola_id == 4 || $user->rola_id == 5))
			$autheticated = 1;
	    }

	    if ($autheticated == 1)
	    {


		if ($request->zamestanec != "n")
		{
			foreach ($users as $user)
			{
				if (iconv('UTF-8', 'ASCII//TRANSLIT', $user->name) == $request->zamestnanec)
				{
					PriradenyZamestnanec::create(['problem_id' => $request->problemID, 'zamestnanec_id' => $user->id]);
				}
			}
		}
		if ($request->priorita != "n")
		{
			$priority = Priorita::all();
			foreach ($priority as $priorita)
			{
				if (iconv('UTF-8', 'ASCII//TRANSLIT', $priorita->priorita) == $request->priorita)
					Problem::where('problem_id', $request->problemID)->update(['priorita_id' => $priorita->priorita_id]);
			}
		}
		if ($request->kategoria != "n")
		{
			$kategorie = KategoriaProblemu::all();
			foreach ($kategorie as $kategoria)
			{
				if(iconv('UTF-8', 'ASCII//TRANSLIT', $kategoria->nazov) == $request->kategoria)
					Problem::where('problem_id', $request->problemID)->update(['kategoria_problemu_id' => $kategoria->kategoria_problemu_id]);
			}
		}
		if ($request->stavProblemu != "n")
		{
			$stavy = StavProblemu::all();
			foreach ($stavy as $stav)
			{
				if (iconv('UTF-8', 'ASCII//TRANSLIT', $stav->nazov) == $request->stavProblemu)
					Problem::where('problem_id', $request->problemID)->update(['stav_problemu_id' => $stav->stav_problemu_id]);
			}
		}
		if ($request->stavRiesenia != "n")
		{
			$stavy = TypStavuRieseniaProblemu::all();
			foreach ($stavy as $stav)
			{
				if (iconv('UTF-8', 'ASCII//TRANSLIT', $stav->nazov) == $request->stavRiesenia)
					StavRieseniaProblemu::create(['problem_id' => $request->problemID, 'typ_stavu_riesenia_problemu_id' => $stav->typ_stavu_riesenia_problemu_id]);
			}
		}
		if ($request->vozidlo != "n")
		{
			$vozidla = Vozidlo::all();
			foreach($vozidla as $vozidlo)
			{
				if (iconv('UTF-8', 'ASCII//TRANSLIT', $vozidlo->SPZ) == $request->vozidlo)
					PriradeneVozidlo::create(['problem_id' => $request->problemID, 'vozidlo_id' => $vozidlo->vozidlo_id]);
			}
		}
		if ($request->popisRiesenia != "n")
		{
			PopisStavuRieseniaProblemu::create(['problem_id' => $request->problemID, 'popis' => $request->popisRiesenia]);
			$last = DB::table('popis_stavu_riesenia_problemu')->latest('popis_stavu_riesenia_problemu_id')->first();

			DB::table('popis_stavu_riesenia_problemu')->where('popis_stavu_riesenia_problemu_id', $last->popis_stavu_riesenia_problemu_id)->update(['verejne' => $request->verejne]);

			return $last->popis_stavu_riesenia_problemu_id;

		}
		else
			return 0;
	    }
	    else
		    return -1;

    }
    //KONIEC DRIENIK
}
