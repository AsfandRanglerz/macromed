<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CompanyUpdateRequest extends FormRequest
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
        $companyId = $this->route('id');
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('companies')->ignore($companyId)
            ],
            'contact_detail' => 'required|numeric',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'zip' => 'required|numeric',
            'website' => [
                'required',
                'string',
                'url'
            ],
        ];
    }
}
