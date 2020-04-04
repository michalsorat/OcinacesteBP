<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Problem extends Model
{
    protected $fillable = ['problem_id', 'poloha', 'popis_problemu', 'priorita_id', 'cesta_id', 'pouzivatel_id', 'stav_riesenia_problemu_id',
        'kategoria_problemu_id', 'stav_problemu_id', 'popis_stavu_riesenia_problemu_id',
        'priradeny_zamestnanec_id', 'priradene_vozidlo_id'];

    protected $table='problem';
    protected $primaryKey ='problem_id';

    protected $attributes = [
        'priorita_id' => 1, //nepriradena
        'cesta_id' => 1, // default
        //'pouzivatel_id' => 1, //default
        'stav_riesenia_problemu_id' => 1, //prijate
        'popis_stavu_riesenia_problemu_id' => 1, //nepriradeny popis
        'priradeny_zamestnanec_id' => 1, //default - admin
        'priradene_vozidlo_id' => 1 //default vozidlo
    ];

    //A Problem can only have one Priorita
    public function Priorita(){
        return $this->belongsTo('App\Models\Priorita', 'priorita_id');
    }

    public function Cesta(){
        return $this->belongsTo('App\Models\Cesta', 'cesta_id');
    }

    public function users(){
        return $this->belongsTo('App\User', 'pouzivatel_id');
    }

    public function StavRieseniaProblemu(){
        return $this->belongsTo('App\Models\StavRieseniaProblemu', 'stav_riesenia_problemu_id');
    }

    public function KategoriaProblemu(){
        return $this->belongsTo('App\Models\KategoriaProblemu', 'kategoria_problemu_id');
    }

    public function StavProblemu(){
        return $this->belongsTo('App\Models\StavProblemu', 'stav_problemu_id');
    }

    public function PopisStavuRieseniaProblemu(){
        return $this->belongsTo('App\Models\PopisStavuRieseniaProblemu', 'popis_stavu_riesenia_problemu_id');
    }

    public function PriradenyZamestnanec(){
        return $this->belongsTo('App\Models\PriradenyZamestnanec', 'priradeny_zamestnanec_id');
    }

    public function PriradeneVozidlo(){
        return $this->belongsTo('App\Models\PriradeneVozidlo', 'priradene_vozidlo_id');
    }
}
