<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RouteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'source'=>$this->source,
            'destination'=> $this->destination,
            'next step'=> $this->next_step,
            'direction'=> $this->direction,
            'distance'=> $this->distance,
            'path'=> $this->path
        ];
    }
}
