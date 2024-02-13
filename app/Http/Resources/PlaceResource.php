<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlaceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'place id'=>$this->id,
            'place name'=> $this->name,
            'region id'=> $this->region_id,
            'guide word'=> $this->guide_word,
            'x coordinate'=>$this->x_coordinate,
            'y coordinate'=>$this->y_coordinate,
            'building id'=>$this->building_id
        ];
    }
}
