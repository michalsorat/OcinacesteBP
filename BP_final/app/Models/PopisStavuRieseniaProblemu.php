<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PopisStavuRieseniaProblemu extends Model
{
    protected $fillable = ['popis_stavu_riesenia_problemu_id', 'popis', 'fotka_riesenia_id'];

    protected $table='popis_stavu_riesenia_problemu';
    protected $primaryKey ='popis_stavu_riesenia_problemu_id';

    public function FotkaRieseniaProblemu(){
        return $this->belongsTo('App\Models\FotkaStavuRieseniaProblemu', 'fotka_riesenia_id');
    }
}
