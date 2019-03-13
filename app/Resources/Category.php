<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Category extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'url' => $this->url,
            'owner_name' => $this->owner->name,
            'owner' => $this->owner,
            'created_at' => $this->created_at->format('d.m.Y'),
            'is_page' => false,
            'is_category' => true,
        ];
    }
}
