<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SalesAgentRequest extends FormRequest
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
        $id = $this->route('category') ?? $this->input('draft_id');
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sales_agents')->ignore($id)
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('sales_agents')->ignore($id)
            ],
            'phone' => [
                'required',
                'numeric',
                'min:11',
                Rule::unique('sales_agents')->ignore($id)
            ],
            'account_number' => [
                'required',
                'unique:user_accounts',
                'min:16',
                'regex:/^[A-Z]{2}\d{2}[A-Z0-9]{1,30}$/',
                function ($attribute, $value, $fail) {
                    if (!$this->isValidIban($value)) {
                        $fail('The account number is not a valid IBAN.');
                    }
                }
            ],
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'location' => 'required',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:1048',
            'account_name' => 'required|string|max:255',
            'account_holder_name' => 'required|string|max:255'
        ];
    }
    private function isValidIban($iban)
    {
        // Example: Basic IBAN format validation
        $iban = strtoupper($iban); // Convert to uppercase

        // Ensure the IBAN matches the expected format (country code + check digits + BBAN)
        if (!preg_match('/^[A-Z]{2}\d{2}[A-Z0-9]{1,30}$/', $iban)) {
            return false;
        }

        // You can implement a more complex checksum validation here
        // For a complete validation, you may need to perform a specific algorithm for IBAN checksum.
        // Here, we're just doing a simple format check.

        return true;
    }
}
