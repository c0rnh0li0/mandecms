<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    public function role_policy() {
        return $this->hasMany('App\RolePolicy', 'policy_id', 'id');
    }
}
