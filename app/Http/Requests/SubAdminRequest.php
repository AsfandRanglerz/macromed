<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SubAdminRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email', // Ensures the email format is valid
                'max:255',
                Rule::unique('users')->ignore($id),
            ],

            'phone' => [
                'required',
                'numeric',
                'min:11',
                Rule::unique('users')->ignore($id),
            ],
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:1048'
        ];
    }
}
