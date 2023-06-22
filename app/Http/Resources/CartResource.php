<?php

namespace App\Http\Resources;

use App\Models\Product;
use Arr;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($cart)
    {
        $productIds   = Arr::pluck($this->cart_items, 'id');
        $products     = Product::whereIn('id', $productIds)->get();
        $productItems = $this->cart_items;

        $products->each(function ($product) use ($productItems) {
            $cartItem              = collect($productItems)->firstWhere('id', $product->id);
            $product->checkoutable = $product->quantity >= $cartItem['quantity'];
            $product->quantity     = $cartItem['quantity'] ?? 0;
        });

        return [
            "id"         => $this->id,
            "user_id"    => $this->user_id,
            "cart_items" => new CartItemsResource($products->toArray()),
            "updated_at" => $this->updated_at,
            "created_at" => $this->created_at,
        ];
    }
}