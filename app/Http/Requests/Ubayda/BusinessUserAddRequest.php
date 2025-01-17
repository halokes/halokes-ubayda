<?php

namespace App\Http\Requests\Ubayda;

use Illuminate\Foundation\Http\FormRequest;

class BusinessUserAddRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'type' => 'required|string|max:100',
            'is_active' => 'nullable|boolean',
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
            'name.required' => 'The name field is required and cannot be left empty.',
            'name.string' => 'The name must be a valid string.',
            'name.max' => 'The name cannot exceed 255 characters.',

            'address.required' => 'The address field is required and cannot be left empty.',
            'address.string' => 'The address must be a valid string.',
            'address.max' => 'The address cannot exceed 500 characters.',

            'type.required' => 'The type field is required and cannot be left empty.',
            'type.string' => 'The type must be a valid string.',
            'type.max' => 'The type cannot exceed 100 characters.',

            'is_active.boolean' => 'The is_active field must be true or false.',
        ];
    }
}
