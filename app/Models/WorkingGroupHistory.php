<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkingGroupHistory extends Model
{
    protected $fillable = ['working_group_id', 'type', 'description'];

    protected $table = 'working_group_histories';
}
