<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCustomer extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|max:255',
            'phone' => 'required|numeric|digits:11|unique:users,phone',
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'confirmpassword' => 'required|same:password',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:1024',
            'profession' => 'required|string|max:255',
            'work_space_name' => 'required|string|max:255',
            'work_space_address' => 'required|string|max:255',
            'work_space_number' => 'required|numeric|digits:11|unique:users,work_space_number',
            'work_space_email' => 'required|email|unique:users,work_space_email|max:255'
        ];
    }
}
