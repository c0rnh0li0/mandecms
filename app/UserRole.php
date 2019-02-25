<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    const DEFAULT_USER_ROLE = 2;
    //

    public function users() {
        return $this->hasMany('App\User');
    }

    public function role_policy() {
        return $this->hasMany('App\RolePolict', 'role_id', 'id');
    }
}
