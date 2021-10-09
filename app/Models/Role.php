<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    public function usersRole(){
        return $this->belongsToMany("App\Models\User",'role_users','role_id','user_id')->withPivot([]);
    }

    public function permissionsRole(){
        return $this->belongsToMany("App\Models\Permission",'role_permissions','role_id','permission_id')->withPivot([]);
    }
}

