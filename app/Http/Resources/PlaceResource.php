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
            'region'=> $this->region,
            'guide word'=> $this->guide_word
        ];
    }
}
