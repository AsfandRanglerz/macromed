<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'thumbnail_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'short_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\s]+$/', // Alphabet and numbers only
                Rule::unique('products')
            ],
            'product_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\s]+$/', // Alphabet and numbers only
                Rule::unique('products')
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products')
            ],
            'product_hts' => [
                'required',
                'string',
                'regex:/^\d{4}(\.\d{2})?(\.\d{2})?$/',
                'unique:products,product_hts'
            ],
            'category_id' => 'required|array',
            'category_id.*' => 'exists:categories,id',
            'brand_id' => 'required|array',
            'brand_id.*' => 'exists:brands,id',
            'certification_id' => 'required|array',
            'certification_id.*' => 'exists:certifications,id',
            'company' => 'required',
            'country' => 'required|string|max:255',
            'sterilizations' => 'required|string|max:255',
            'product_use_status' => 'required|string|max:255',
            'product_commission' => ['required', 'numeric', 'between:0,49.999'],
            'video_link' => 'nullable|string|max:255',
            'short_description' => 'required|string',
            'long_description' => 'required|string',
            'buyer_type' => 'required',
            'product_class' => 'required',
            'supplier_delivery_time' => 'required|regex:/^\d{1,3}$/',
            'supplier_name' => 'required',
            'delivery_period' => 'required|regex:/^\d{1,3}$/',
            'self_life' => 'required|numeric|digits:4',
            'federal_tax' => 'required|regex:/^\d{1,3}(\.\d+)?%$/',
            'provincial_tax' => 'required|regex:/^\d{1,3}(\.\d+)?%$/',
            'material_id' => 'required|array',
            'material_id.*' => 'exists:main_materials,id',
            'taxes.*.tax_per_city' => 'required',
            'taxes.*.local_tax' => 'required|regex:/^\d{1,3}(\.\d+)?%$/',
        ];
    }

    public function messages()
    {
        return [
            'category_id' => 'Category is required.',
            'category_id.*' => 'Category is required.',
            'brand_id' => 'Brand is required.',
            'brand_id.*' => 'Brand is required.',
            'certification_id' => 'Certification is required.',
            'certification_id.*' => 'Certification is required.',
            'material_id' => 'Main Material is required.',
            'material_id.*' => 'Main Material is required.',
            'short_name.regex' => 'The short name must contain only letters and numbers.',
            'product_name.regex' => 'The product name must contain only letters and numbers.',
            'product_commission.min' => 'The product commission must be at least 50.',
            'supplier_delivery_time.regex' => 'The supplier delivery time must be a number with 1 to 3 digits.',
            'delivery_period.regex' => 'The delivery period must be a number with 1 to 3 digits.',
            'self_life.digits' => 'The self-life must be exactly 4 digits.',
            'federal_tax.regex' => 'The federal tax must be a number with at least 3 digits followed by a % sign.',
            'provincial_tax.regex' => 'The provincial tax must be a number with at least 3 digits followed by a % sign.',
            'product_hts.required' => 'The HTS code is required.',
            'product_hts.string' => 'The HTS code must be a valid string.',
            'product_hts.regex' => 'The HTS code must be in the format XXXX or XXXX.XX or XXXX.XX.XX (where X is a digit).',
            'product_hts.unique' => 'The HTS code has already been used. Please enter a unique code.',
            'taxes.*.tax_per_city.required' => 'Tax per city is required.',
            'taxes.*.local_tax.required' => 'Local Tax is required.',
            'taxes.*.local_tax.regex' => 'The local tax must be a number with at least 3 digits followed by a % sign.',
        ];
    }
}
