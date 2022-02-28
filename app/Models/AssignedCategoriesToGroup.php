<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignedCategoriesToGroup extends Model
{
    protected $fillable = ['kategoria_problemu_id', 'working_group_id'];

    protected $table = 'assigned_categories_to_groups';

}
