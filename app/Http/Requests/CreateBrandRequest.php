<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateBrandRequest extends FormRequest
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
        $brandId = $this->route('category') ?? $this->input('draft_id');
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('brands')->ignore($brandId)
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('brands')->ignore($brandId)
            ],
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:1048',
            'contact_detail' => 'required|numeric|regex:/^\+[1-9]{1}[0-9]{1,14}$/',
            'owner' => 'required',
            'company' => 'required',
            'company_country' => 'required'

        ];
    }
}
