<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Page extends JsonResource
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
            'url' => $this->url,
            'description' => $this->description,
            'body' => $this->body,
            'template_name' => $this->template->name,
            'owner_name' => $this->owner->name,
            'template' => $this->template,
            'owner' => $this->owner,
            'category' => $this->category,
            'created_at' => $this->created_at->format('d.m.Y'),
            'menu' => $this->menu,
            'is_page' => true,
            'is_category' => false,
        ];
    }
}
