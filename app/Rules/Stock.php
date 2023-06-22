<?php

namespace App\Rules;

use App\Models\Product;
use Exception;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\Rule;
use Str;

class Stock implements Rule, DataAwareRule
{

    protected $data = [];
    protected $msg;
    protected $type;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {

        try {
            // check is uuid 
            if (!Str::isUuid($this->data['product_id'])) {
                throw new Exception("product id must be a valid UUID.");
            }
            // find cart data
            $cart = auth()->user()->cart()->first();

            // find the product
            $product = Product::find($this->data['product_id']);

            if ($product === null) {
                throw new Exception("Product not found");
            }

            if ($cart === null) {
                $value <= $product->quantity ?: throw new Exception("Quantity exceeds available stock");
            } else {
                foreach ($cart->cart_items as $item) {
                    if ($this->type == 'add') {
                        if ($item["id"] === $product->id) {
                            if (($item["quantity"] + $value) > $product->quantity) {
                                throw new Exception("Quantity exceeds available stock");
                            }
                        } else {
                            if ($value > $product->quantity) {
                                throw new Exception("Quantity exceeds available stock");
                            }
                        }
                    } elseif ($this->type == 'update') {
                        $value <= $product->quantity ?: throw new Exception("Quantity exceeds available stock");
                    }
                }
            }

            return true;
        } catch (\Throwable $th) {
            $this->msg = $th->getMessage();
            return false;
        }

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The stock is invalid. ' . $this->msg;
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }
}