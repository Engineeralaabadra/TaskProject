<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
    public function rolesPermission(){
        return $this->belongsToMany("App\Models\Role",'role_permissions','permission_id','role_id')->withPivot([]);
    }
}
