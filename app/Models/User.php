<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function hasAbility($ability,$pageName){
         $allRolesUser=User::find($this->id)->rolesUser()->get(); //get all roles for a user
         foreach($allRolesUser as $role){
             $permissionsRoleUser=Role::find($role->id)->permissionsRole()->get();
            // dd($permissionsRoleUser);
             $flag=false;
             foreach($permissionsRoleUser as $per){

                     if($per->action==$ability&&$per->page_name==$pageName){
                         $flag=true;
                  }
            }
            
            return $flag;
            
        }
    }

    public function rolesUser(){
        return $this->belongsToMany("App\Models\Role",'role_users','user_id','role_id');
    }

}
