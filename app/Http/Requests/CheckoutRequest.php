<?php

namespace App\Http\Requests;

use App\Models\Cart;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $cart = Cart::where('user_id', auth()->user()->id)->first();

        if (!$cart) {
            throw new ValidationException($this->validator,
                response()->json(
                    [
                        "status" => false,
                        "messages" => "The given data was invalid.",
                        "errors"   => [
                            "cart" => "Cart not found.",
                        ]
                    ], Response::HTTP_UNPROCESSABLE_ENTITY
                )
            );
        }

        return [
            "product_id" => [
                "bail",
                "required",
                "array",
                "exists:products,id",
                Rule::in(array_column($cart["cart_items"], 'id')),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.in' => 'The product is not in the cart',
        ];
    }
}