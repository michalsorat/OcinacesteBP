<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vozidlo extends Model
{
    protected $fillable = ['vozidlo_id', 'oznacenie', 'SPZ', 'pocet_najazdenych_km', 'poznamka', 'working_group_id'];

    protected $attributes = [
        'working_group_id' => 0, //nepriradená pracovná čata
        'poznamka' => 'Žiadna poznámka', //default
    ];

    protected $table='vozidlo';
    protected $primaryKey ='vozidlo_id';
}
