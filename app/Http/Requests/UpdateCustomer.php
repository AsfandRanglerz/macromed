<?php

namespace App\Http\Requests;

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
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('id');
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|numeric|min:11,' . $id,
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:1048',
            'profession' => 'required|string|max:255',
            'work_space_name' => 'required|string|max:255',
            'work_space_address' => 'required|string|max:255',
            'work_space_number' => 'required|numeric|digits:11|unique:users,work_space_number',
            'work_space_email' => 'required|email|unique:users,work_space_email|max:255' . $id,
            'location' => 'required|string|max:255',
        ];
    }
}
