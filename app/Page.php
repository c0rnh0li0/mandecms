<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    public function role() {
        return $this->hasOne(Template::class);
    }

    public function owner() {
        return $this->belongsTo(User::class);
    }

    public function tags() {
        return $this->belongsToMany(Tag::class);
    }
}
