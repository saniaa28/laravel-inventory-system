<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockMovementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id'  => ['required', 'integer', 'exists:products,id'],
            'supplier_id' => ['nullable', 'integer', 'exists:suppliers,id'],
            'quantity'    => ['required', 'integer', 'min:1'],
            'date'        => ['required', 'date'],
            'notes'       => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.min' => 'Quantity must be greater than zero.',
        ];
    }
}
