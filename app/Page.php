<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    public function template() {
        //return $this->belongsTo('App\Template', 'template_id', 'id');
        return $this->hasOne('App\Template', 'id', 'template_id');
    }

    public function owner() {
        //return $this->belongsTo('App\User', 'user_id', 'id');
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function tags() {
        return $this->belongsToMany(Tag::class);
    }

    public function category() {
        return $this->belongsTo('App\Category', 'category_id', 'id');
    }

    public function menu() {
        return $this->belongsTo('App\Menu', 'page_id', 'id');
    }
}
