<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    public function parent() {
        return $this->belongsTo('App\Menu', 'parent_id', 'id');
    }

    public function page() {
        return $this->belongsTo('App\Page', 'page_id', 'id');
    }

    public function category() {
        return $this->belongsTo('App\Category', 'category_id', 'id');
    }
}
