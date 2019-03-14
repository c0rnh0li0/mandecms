<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = [
        'site_logo',
        'site_name',
        'site_metatags',
        'site_description',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'contact_email',
    ];
}
