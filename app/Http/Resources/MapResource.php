<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MapResource extends JsonResource
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
            'topLeft' => [
                'x' => $this->topLeft_x,
                'y' => $this->topLeft_y,
            ],
            'topRight' => [
                'x' => $this->topRight_x,
                'y' => $this->topRight_y,
            ],
            'bottomLeft' => [
                'x' => $this->bottomLeft_x,
                'y' => $this->bottomLeft_y,
            ],
            'bottomRight' => [
                'x' => $this->bottomRight_x,
                'y' => $this->bottomRight_y,
            ],
            'text' => $this->text,
        ];
    }
}

