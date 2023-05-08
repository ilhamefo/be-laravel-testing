<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            "birth_date" => $this->birth_date,
            "birth_place" => $this->birth_place,
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'nik' => $this->nik,
            'npwp' => $this->npwp,
            'address' => $this->address,
            'mother_name' => $this->mother_name,
            'gender' => $this->genderDetails,
            'subdistrict' => new SubdistrictResource($this->sub_district), // `${item.sub_district}, ${item.district}, ${item.city}, ${item.province}, ${item.zipcode}.`
            'occupation_id' => $this->occupation_id,
            'company_name' => $this->company_name,
            'company_address' => $this->company_address,
            'company_subdistrict' => new SubdistrictResource($this->companySubdistrict),
            'line_of_business_id' => $this->line_of_business_id,
            'job_title_id' => $this->job_title_id,
            'gross_income_id' => $this->gross_income_id,
            'income_free_text' => $this->income_free_text,
            'source_of_fund_id' => $this->source_of_fund_id,
            'source_of_fund_free_text' => $this->source_of_fund_free_text,
        ];
    }
}
