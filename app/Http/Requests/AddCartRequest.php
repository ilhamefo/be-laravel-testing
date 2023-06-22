<?php

namespace App\Http\Requests;

use App\Rules\Stock;
use Illuminate\Foundation\Http\FormRequest;

class AddCartRequest extends FormRequest
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
            "product_id" => ["required", "uuid", "exists:products,id"],
            "quantity"   => ["required", "min:1", "numeric", new Stock("add"), "int"]
        ];
    }
}