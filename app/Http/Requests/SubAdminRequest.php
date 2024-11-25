<?php

namespace App\Http\Requests;

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
        return false;
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
            'user_email' => 'required|email|max:255|unique:users,user_email,' . $id,
            'phone' => 'required|numeric|min:11,' . $id,
            'user_password' => 'required|string|min:8|max:255',
            'confirmpassword' => 'required|same:user_password',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:1048'
        ];
    }
}
