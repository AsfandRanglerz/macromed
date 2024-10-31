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
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|string|min:8|max:255',
            'phone' => 'required|numeric|unique:users|min:11',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'location' => 'required',
            'confirmpassword' => 'required|same:password',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:1048',
            'profession' => 'required',
            'work_space_name' => 'required',
            'work_space_address' => 'required',
            'work_space_number' => 'required|numeric|unique:users|min:11',
            'work_space_email' => 'required|work_space_email|unique:users|max:255'
        ];
    }
}
