<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProvinceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "country" => new CountryResource($this->countryDetails),
            "code" => $this->code,
            "name" => $this->name,
            "status" => $this->status,
            "cirt_mapping" => $this->cirt_mapping,
            "cirt_mapping_name" => $this->cirt_mapping_name
        ];
    }
}