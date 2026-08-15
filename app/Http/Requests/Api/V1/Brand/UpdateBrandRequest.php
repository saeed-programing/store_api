<?php

namespace App\Http\Requests\Api\V1\Brand;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBrandRequest extends FormRequest
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
        return [
            'name' => [
                'required',
                'min:2',
                Rule::unique('brands', 'name')->ignore($this->brand->id),
            ],

            'display_name' => [
                'required',
                'min:3',
                Rule::unique('brands', 'display_name')->ignore($this->brand->id),
            ],
        ];
    }
}
