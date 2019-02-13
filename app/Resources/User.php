<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'user_role' => $this->role->name,
            'user_avatar' => '/storage/user_avatars/' . $this->user_avatar,
            'created_at' => $this->created_at->format('d.m.Y'),
        ];
    }
    /*public function with($request) {
        return [
            'version' => '1.0.0',
            'author_url' => url('http://google.com')
        ];
    }*/

}
