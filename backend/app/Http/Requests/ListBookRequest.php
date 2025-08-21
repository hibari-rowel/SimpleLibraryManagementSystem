<?php

namespace App\Http\Requests;

use App\Enum\OrderBy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ListBookRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'order_by' => ['nullable', 'string'],
            'order' => ['nullable', new Enum(OrderBy::class)],
        ];
    }
}
