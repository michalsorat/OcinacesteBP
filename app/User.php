<?php

namespace App;

use App\Models\WorkingGroup;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
	use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','name', 'email', 'password', 'rola_id', 'working_group_id'
    ];
    protected $table = 'users';
    protected $primaryKey ='id';

    protected $attributes = [
        'rola_id' => 1, //nepriradena - default zaregistrovany registeredCitizen
        'working_group_id' => 0, //nepriradená pracovná čata
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function rola(){
        return $this->belongsTo('App\Rola', 'rola_id');
    }


    //Ak by sme chceli overiť, či má niektorú z rolí, použili by sme metódu authorizeRoles
    public function authorizeRoles($roles)
    {
        if ($this->hasAnyRole($roles)) {
            return true;
        }
        abort(401, 'This action is unauthorized.');
    }



    public function hasAnyRole($roles)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        } else {
            if ($this->hasRole($roles)) {
                return true;
            }
        }

        return false;
    }

    //Ak chceme overiť, či má používateľ danú rolu, použijeme metódu hasRole
    public function hasRole($role)
    {
        if ($this->rola()->where('nazov', $role)->first()) {
            return true;
        }
        return false;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function workingGroup() {
        $this->belongsTo(WorkingGroup::class);
    }
}
