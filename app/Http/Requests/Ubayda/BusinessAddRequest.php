<?php

namespace App\Http\Requests\Ubayda;

use Illuminate\Foundation\Http\FormRequest;

class BusinessAddRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Set to true if you don't have specific authorization logic
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'address' => 'required|string',
            'type' => 'required',
            'is_active' => 'nullable|boolean|default:true',
        ];
    }

    /**
     * Get custom error messages for validation.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'address.required' => 'The address field is required.',
            'type.required' => 'The type field is required.',
        ];
    }
}
