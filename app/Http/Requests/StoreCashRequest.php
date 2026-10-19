<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCashRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|in:in,out',
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
        ];
    }
}
