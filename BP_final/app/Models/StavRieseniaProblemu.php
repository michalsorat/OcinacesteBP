<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StavRieseniaProblemu extends Model
{
    protected $fillable = ['stav_riesenia_problemu_id', 'typ_stavu_riesenia_problemu_id'];
    protected $table='stav_riesenia_problemu';
    protected $primaryKey ='stav_riesenia_problemu_id';

    public function TypStavuRieseniaProblemu(){
        return $this->belongsTo('App\Models\TypStavuRieseniaProblemu', 'typ_stavu_riesenia_problemu_id');
    }
}
