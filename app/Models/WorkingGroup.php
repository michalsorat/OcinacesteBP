<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class WorkingGroup extends Model
{
    protected $table = 'working_groups';

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function vehicle()
    {
        return $this->hasOne(Vozidlo::class);
    }

    public function assignedCategories()
    {
        return $this->hasmany(AssignedCategoriesToGroup::class);
    }

    public function assignedProblems()
    {
        return $this->hasmany(Problem::class, 'working_group_id');
    }

}
