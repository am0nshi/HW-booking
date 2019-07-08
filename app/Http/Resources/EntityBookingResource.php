<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EntityBookingResource extends JsonResource
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
            'user' => new UserResource($this->whenLoaded('User')),
            'booked_from' => $this->from->format('Y-m-d\TH:i:s\Z'),
            'booked_to' => $this->to->format('Y-m-d\TH:i:s\Z'),
        ];
    }
}
