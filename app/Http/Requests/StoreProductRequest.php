<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Hak akses sudah dijaga oleh middleware role di route
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id'   => ['required', 'integer', 'exists:categories,id'],
            'sku'           => ['required', 'string', 'max:80', 'unique:products,sku'],
            'name'          => ['required', 'string', 'max:150'],
            'description'   => ['nullable', 'string', 'max:1000'],
            'price'         => ['required', 'numeric', 'min:0'],
            'stock'         => ['required', 'integer', 'min:1'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'sku.unique'   => 'SKU must be unique.',
            'stock.min'    => 'Initial stock must be greater than zero.',
            'price.min'    => 'Price cannot be negative.',
            'minimum_stock.min' => 'Minimum stock cannot be negative.',
        ];
    }
}
