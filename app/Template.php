<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    public function sections() {
        return $this->hasMany(TemplateSection::class);
    }

    protected $attributes = [
        'thumb' => 'default_thumb.png',
    ];
}
