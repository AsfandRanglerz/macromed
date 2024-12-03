<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomer extends FormRequest
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
                'email',
                'max:255',
                Rule::unique('users')->ignore($id)
            ],
            'phone' => [
                'required',
                'numeric',
                'min:11',
                Rule::unique('users')->ignore($id)
            ],
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:1024',
            'profession' => 'required|string|max:255',
            'work_space_name' => 'required|string|max:255',
            'work_space_address' => 'required|string|max:255',
            'work_space_number' => [
                'required',
                'digits:11',
                'numeric',
                Rule::unique('users')->ignore($id)
            ],
            'work_space_email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($id)
            ],
            'location' => 'required|string|max:255',
        ];
    }
}
