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

    public function policies() {
        return $this->belongsToMany(Policy::class);
    }
}
