<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Problem extends Model
{
    protected $fillable = ['problem_id', 'poloha', 'address', 'popis_problemu', 'priorita_id', 'pouzivatel_id',
        'kategoria_problemu_id', 'stav_problemu_id', 'isBump', 'working_group_id', 'detection_count', 'detection_speed_kmh'
    ];

    protected $table = 'problem';
    protected $primaryKey = 'problem_id';

    protected $attributes = [
        'priorita_id' => 1, //nepriradena
        'working_group_id' => 0, // default- nepriradena
    ];

    //A Problem can only have one Priorita
    public function Priorita(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Priorita', 'priorita_id');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\User', 'pouzivatel_id');
    }

    public function KategoriaProblemu(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\KategoriaProblemu', 'kategoria_problemu_id');
    }


    public function StavProblemu(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\StavProblemu', 'stav_problemu_id');
    }

    public function StavRieseniaProblemu(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(StavRieseniaProblemu::class, 'problem_id')->orderByDesc('created_at');
    }

    public function PopisyRiesenia(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PopisStavuRieseniaProblemu::class, 'problem_id')->orderByDesc('created_at');
    }

    public function problemImage(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FotkaProblemu::class, 'problem_id');
    }

    public function problemSolImage(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FotkaRieseniaProblemu::class, 'problem_id');
    }

    public function problemHistory(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProblemHistoryRecord::class, 'problem_id')->orderBy('created_at', 'desc');
    }
}
