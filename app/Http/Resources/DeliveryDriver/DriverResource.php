<?php

namespace App\Http\Resources\DeliveryDriver;

use App\Http\Resources\MediaCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'surname' => $this->surname,
            'email' => $this->email,
            'landline_phone' => $this->landline_phone,
            'mobile_phone' => $this->mobile_phone,
            'address_line_1' => $this->address_line_1,
            'town' => $this->town,
            'city' => $this->city,
            'county' => $this->county,
            'postcode' => $this->postcode,
            'registration_status' => $this->registration_status,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'vehciles' => DeliveryVehicleResource::collection($this->vehicles),
            'media' => $this->whenLoaded('media', new MediaCollection($this->media)),
        ];
    }
}
