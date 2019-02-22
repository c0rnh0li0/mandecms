<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RolePolicy extends Model
{
    public function policy() {
        return $this->belongsTo('App\Policy', 'id');
    }

    public function role() {
        return $this->belongsTo('App\UserRole', 'id');
    }
}
