<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SterilizationsRequest extends FormRequest
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
                Rule::unique('sterilizations')->ignore($id),

            ],
        ];
    }
}
