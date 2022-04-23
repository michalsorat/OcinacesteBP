<?php

namespace App\Traits;

use App\Models\Problem;

trait ProblemTrait {
    public function checkIfProblemExists($radius_meters, $latitude, $longitude, $isBump) {
        // Find any problems which are closer than specified radius
        return Problem::selectRaw("problem_id,
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
}
