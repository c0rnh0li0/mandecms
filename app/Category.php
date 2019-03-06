<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function owner() {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }

    public function pages() {
        return $this->hasMany(Page::class);
    }
}
