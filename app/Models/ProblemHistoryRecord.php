<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProblemHistoryRecord extends Model
{
    protected $fillable = ['problem_id', 'type', 'description'];

    protected $table = 'problem_history_records';
}
