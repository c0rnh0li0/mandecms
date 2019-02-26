<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemplateSection extends Model
{
    public function template() {
        return $this->belongsTo(Template::class);
    }
}
