<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $this->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'sale_price' => 'nullable|numeric|lt:price',
            'quantity' => 'required|integer',
            'brand_id' => 'required|integer|exists:brands,id',
            'category_id' => 'required|integer|exists:categories,id',
            'shop_id' => 'required|integer|exists:shops,id',
            'is_featured' => 'nullable|integer',
            'status' => 'required|string|in:draft,published,trashed',
            'total_view' => 'nullable|integer',
            'total_searched' => 'nullable|integer',
            'total_ordered' => 'nullable|integer',
        ];
    }
}
