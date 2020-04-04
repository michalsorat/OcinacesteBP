<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotkaStavuRieseniaProblemu extends Model
{
    protected $fillable = ['fotka_stavu_riesenia_problemu_id', 'cesta_k_suboru'];

    protected $table='fotka_stavu_riesenia_problemu';
    protected $primaryKey ='fotka_stavu_riesenia_problemu_id';


}
