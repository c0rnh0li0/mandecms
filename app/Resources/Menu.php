<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Menu extends JsonResource
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
            'parent_id' => $this->parent_id,
            'name' => $this->name,
            'order' => $this->order,
            'slug' => $this->slug,
            'visible' => $this->visible,
            'page_id' => $this->page_id,
            'page' => $this->page,
            'children' => $this->children,
            'category_id' => $this->category_id,
            'category' => $this->category,
            'created_at' => $this->created_at->format('d.m.Y'),
        ];
    }
}
