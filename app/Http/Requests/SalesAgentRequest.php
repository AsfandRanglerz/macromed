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
            'account_number' => 'required|numeric|unique:user_accounts|min:16,' . $id,
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'location' => 'required',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:1048',
            'account_name' => 'required|string|max:255',
            'account_holder_name' => 'required|string|max:255'
        ];
    }
}
