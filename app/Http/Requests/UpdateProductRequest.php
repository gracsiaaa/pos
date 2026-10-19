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

    public function rules(): array
    {
        $productId = $this->route('product')->id ?? $this->route('product');

        return [
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'sku' => ['required', 'string', 'max:50', Rule::unique('products')->ignore($productId)],
            'barcode' => ['nullable', 'string', 'max:50', Rule::unique('products')->ignore($productId)],
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            // Note: Update product shouldn't directly update stock. Stock must go through StockMovement.
            // But we might allow editing min_stock.
            'min_stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'boolean',
        ];
    }
}
