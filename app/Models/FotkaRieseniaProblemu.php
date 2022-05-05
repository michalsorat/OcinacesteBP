<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotkaRieseniaProblemu extends Model
{
    protected $fillable = ['fotka_riesenia_problemu_id', 'nazov_suboru', 'problem_id'];

    protected $table='fotka_riesenia_problemu';
    protected $primaryKey ='fotka_riesenia_problemu_id';
}
