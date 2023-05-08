<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
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
            "province" => new ProvinceResource($this->provinceDetails),
            "code" => $this->code,
            "name" => $this->name,
            "mapping_name" => $this->mapping_name,
            "type" => $this->type,
            "status" => $this->status
        ];
    }
}