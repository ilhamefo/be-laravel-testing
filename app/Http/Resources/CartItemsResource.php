<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartItemsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // dd($this->resource, $this->cart_items);
        return array_map(function ($item) {
            return [
                "id"              => $item["id"],
                "name"            => $item["name"],
                "quantity"        => $item["quantity"],
                "subtotal"        => $item["quantity"] * $item["price"],
                "is_checkoutable" => $item["checkoutable"],
            ];
        }, $this->resource);
    }
}