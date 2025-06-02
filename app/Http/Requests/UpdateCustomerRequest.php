<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
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
            'name' => 'sometimes|required|string|max:255',
            'contact_no' => 'sometimes|required|string|max:15',
            'email' => [
                'nullable',
                'email',
                Rule::unique('customers', 'email')->ignore($this->customer)
            ],
            'cnic' => [
                'nullable',
                Rule::unique('customers', 'cnic')->ignore($this->customer)
            ],
            'birth_date' => 'nullable|date',
            'anniversary_date' => 'nullable|date',
            'cash_balance' => 'nullable|numeric',
            'points' => 'nullable|integer',
            'is_active' => 'boolean',
        ];
    }
}
