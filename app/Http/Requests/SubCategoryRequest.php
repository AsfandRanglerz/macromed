<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SubCategoryRequest extends FormRequest
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
        $subCategoryId = $this->route('category') ?? $this->input('draft_id');
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sub_categories')->ignore($subCategoryId),
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sub_categories')->ignore($subCategoryId),
            ],
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function messages()
    {
        return [
            'category_id.required' => 'Select Category; it is required.',
            'category_id.exists' => 'The selected category is invalid.',
        ];
    }
}
