<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BuildingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'buiding name'=>$this->name,
            'address'=>$this->address,
            'longitude'=>$this->longitude,
            'description'=>$this->description,
            'latitude'=>$this->latitude,
            'photo'=>$this->photo,
        ];
    }
}
