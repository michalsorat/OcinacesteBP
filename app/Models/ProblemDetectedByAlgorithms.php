<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProblemDetectedByAlgorithms extends Model
{
    public $timestamps = false;
    protected $fillable = ['detected_by_algorithm_id', 'algorithm', 'problem_id'];
    protected $hidden = ['problem_id'];
    protected $table='problem_detected_by_algorithms';
    protected $primaryKey ='detected_by_algorithm_id';
}
