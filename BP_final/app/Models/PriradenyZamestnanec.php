<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriradenyZamestnanec extends Model
{
    protected $fillable = ['priradeny_zamestnanec_id', 'zamestnanec_id'];

    protected $table='priradeny_zamestnanec';
    protected $primaryKey ='priradeny_zamestnanec_id';

    public function users(){
        return $this->belongsTo('App\User', 'zamestnanec_id');
    }
}
