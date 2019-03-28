<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    public function parent()
    {
        return $this->belongsTo('App\Menu', 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany('App\Menu', 'parent_id', 'id');
    }

    public function page()
    {
        return $this->hasOne('App\Page', 'id', 'page_id');
    }

    public function category()
    {
        return $this->hasOne('App\Category', 'id', 'category_id');
    }

    public function gallery()
    {
        return $this->hasOne('App\Category', 'id', 'gallery_id');
    }
}
