<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotkaProblemu extends Model
{
    protected $fillable = ['fotka_problemu_id', 'nazov_suboru', 'problem_id'];

    protected $table='fotka_problemu';
    protected $primaryKey ='fotka_problemu_id';


}
