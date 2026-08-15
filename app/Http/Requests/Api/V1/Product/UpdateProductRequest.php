<?php

namespace App\Http\Requests\Api\V1\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $product = $this->route('product');

        return [
            'name' => [
                'required',
                'string',
                'min:3',
                Rule::unique('products', 'name')->ignore($product->id),
            ],
            'brand_id' => 'required|exists:brands,id|integer',
            'category_id' => 'required|exists:categories,id',
            'primary_image' => 'nullable|image',
            'description' => 'required|string|min:3',
            'price' => 'required|integer',
            'quantity' => 'required|integer',
            'delivery_amount' => 'required|integer',
            'images.*' => 'nullable|image',
        ];
    }
}
