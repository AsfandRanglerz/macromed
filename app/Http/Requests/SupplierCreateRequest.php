<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SupplierCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('suppliers')
            ],
            'email' => [
                'required',
                'string',
                'max:255',
                Rule::unique('suppliers')
            ],
            'alternate_email' => [
                'required',
                'string',
                'max:255',
                Rule::unique('suppliers')
            ],
            'phone_number' => [
                'required',
                'string',
                'regex:/^\+[1-9]{1}[0-9]{1,14}$/',
                Rule::unique('suppliers')
            ],
            'alternate_phone_number' => [
                'required',
                'string',
                'regex:/^\+[1-9]{1}[0-9]{1,14}$/',
                Rule::unique('suppliers')
            ],
            'whats_app' => [
                'required',
                'string',
                'regex:/^\+[1-9]{1}[0-9]{1,14}$/', // E.164 format: + followed by 1-15 digits
                Rule::unique('suppliers')
            ],
            'poc' => [
                'required',
                'numeric',
                Rule::unique('suppliers')
            ],
            'address' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'website' => [
                'required',
                'string',
                'url'
            ],
        ];
    }
}
