<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubdistrictResource extends JsonResource
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
            "id"=> $this->id,
            "district_id"=> $this->district_id,
            "code"=> $this->code,
            "name"=> $this->name,
            "postal_code"=> $this->postal_code,
            "status"=> $this->status,
            "tsv"=> $this->tsv,
            "district"=> new DistrictResource($this->districtDetails)
        ];
    }
}
