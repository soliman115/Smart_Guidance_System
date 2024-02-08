<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'region name'=>$this->name,
            'x coordinate'=> $this->x_coordinate,
            'y coordinate'=> $this->y_coordinate,  
        ];
    }
}
