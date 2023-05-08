<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
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
            "name" => $this->name,
            "mapping_name" => $this->mapping_name,
            "country_code" => $this->country_code,
            "is_high_risk_country" => $this->is_high_risk_country,
            "min_phone_digit" => $this->min_phone_digit,
            "max_phone_digit" => $this->max_phone_digit,
            "status" => $this->status,
        ];
    }
}
