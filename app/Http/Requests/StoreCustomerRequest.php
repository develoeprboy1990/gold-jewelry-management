<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends FormRequest
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
            'name'             => 'required|string|max:255',
            'contact_no'       => 'required|string|max:15',
            'email'            => 'nullable|email|unique:customers,email',
            'cnic'             => 'nullable|string|unique:customers,cnic|max:20',
            'birth_date'       => 'nullable|date',
            'anniversary_date' => 'nullable|date',
            'cash_balance'     => 'nullable|numeric',
            'points'           => 'nullable|integer',
            'is_active'        => 'boolean',
        ];
    }
}
