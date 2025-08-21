<?php

namespace App\Http\Requests;

use App\Models\Fine;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFineRequest extends FormRequest
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
        return Fine::getFieldValidations(request()->all());
    }

    public function messages(): array
    {
        return Fine::getValidationMessages();
    }
}
