<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriradeneVozidlo extends Model
{
    protected $fillable = ['priradene_vozidlo_id', 'vozidlo_id'];

    protected $table='priradene_vozidlo';
    protected $primaryKey ='priradene_vozidlo_id';

    public function Vozidlo(){
        return $this->belongsTo('App\Models\Vozidlo', 'vozidlo_id');
    }
}
