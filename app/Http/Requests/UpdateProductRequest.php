<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // Stok tidak ada di sini: stok hanya boleh berubah lewat Stock In dan Stock Out
    public function rules(): array
    {
        return [
            'category_id'   => ['required', 'integer', 'exists:categories,id'],
            'sku'           => ['required', 'string', 'max:80', Rule::unique('products', 'sku')->ignore($this->route('product'))],
            'name'          => ['required', 'string', 'max:150'],
            'description'   => ['nullable', 'string', 'max:1000'],
            'price'         => ['required', 'numeric', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'sku.unique'   => 'SKU must be unique.',
            'price.min'    => 'Price cannot be negative.',
            'minimum_stock.min' => 'Minimum stock cannot be negative.',
        ];
    }
}
