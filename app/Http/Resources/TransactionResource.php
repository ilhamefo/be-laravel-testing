<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            "id"                 => $this->id,
            "amount"             => $this->amount,
            "description"        => $this->description,
            "transaction_detail" => $this->transaction_detail()->get()->each(function ($item) {
                $item->product = $item->products()->get()->toArray();
            }),
            "user"               => $this->user(),
        ];
    }
}