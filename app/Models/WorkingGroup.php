<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class WorkingGroup extends Model
{
    protected $table = 'working_groups';

    public function users(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class);
    }

    public function vehicle(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Vozidlo::class);
    }

    public function assignedCategories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasmany(AssignedCategoriesToGroup::class);
    }

    public function assignedProblems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasmany(Problem::class, 'working_group_id');
    }

    public function history(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasmany(WorkingGroupHistory::class, 'working_group_id')->orderBy('created_at', 'desc');
    }

}
