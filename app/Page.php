<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    public function template() {
        return $this->belongsTo('App\Template', 'template_id', 'id');
    }

    public function owner() {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }

    public function tags() {
        return $this->belongsToMany(Tag::class);
    }

    public function category() {
        return $this->belongsTo('App\Category', 'category_id', 'id');
    }
}
