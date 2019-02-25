<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    public function roles() {
        return $this->belongsToMany(UserRole::class);
    }
}
