<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Page as PageModel;

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
            'hero_image' => $this->hero_image,
            'template' => $this->template,
            'page_metatags' => $this->page_metatags,
            'owner' => $this->owner,
            'category' => $this->category,
            'category_id' => $this->category_id,
            'created_at' => $this->created_at->format('d.m.Y'),
            'menu' => $this->menu,
            'is_page' => true,
            'is_category' => false,
        ];
    }
}
