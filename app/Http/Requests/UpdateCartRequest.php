<?php

namespace App\Http\Requests;

use App\Rules\Stock;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCartRequest extends FormRequest
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
        return [
            "product_id" => "bail|required|uuid|exists:products,id",
            "quantity"   => ["required","min:0","numeric", new Stock("update"), "int"]
        ];
    }
}