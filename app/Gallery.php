<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table = 'categories';

    public function owner() {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }

    public function images() {
        return $this->hasMany(Image::class);
    }
}
