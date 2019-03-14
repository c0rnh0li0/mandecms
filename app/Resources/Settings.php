<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Settings extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'site_logo' => $this->site_logo != 'null' ? $this->site_logo  : '',
            'site_name' => $this->site_name != 'null' ? $this->site_name : '',
            'site_metatags' => $this->site_metatags != 'null' ? $this->site_metatags : '',
            'site_description' => $this->site_description != 'null' ? $this->site_description : '',
            'facebook_url' => $this->facebook_url != 'null' ? $this->facebook_url : '',
            'instagram_url' => $this->instagram_url != 'null' ? $this->instagram_url : '',
            'twitter_url' => $this->twitter_url != 'null' ? $this->twitter_url : '',
            'contact_email' => $this->contact_email != 'null' ? $this->contact_email : '',
        ];
    }
}
