<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductVariantStore extends FormRequest
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
            'variants.*.m_p_n' => 'required|string',
            'variants.*.s_k_u' => 'required|unique:product_varaints|max:255',
            'variants.*.packing' => 'required|string',
            'variants.*.unit' => 'required|string',
            'variants.*.tooltip_information' => 'required|string',
            'variants.*.quantity' => 'required|integer',
            'variants.*.price_per_unit' => 'required|numeric',
            'variants.*.selling_price_per_unit' => 'required|numeric',
            'variants.*.actual_weight' => 'required|numeric',
            'variants.*.shipping_weight' => 'required|numeric',
            'variants.*.shipping_chargeable_weight' => 'required|numeric',
            'variants.*.description' => 'required|string',
        ];
    }
    public function messages()

    {
        return [
            'variants.*.s_k_u.required' => 'SKU is required.',
            'variants.*.s_k_u.string' => 'SKU must be a string.',
            'variants.*.s_k_u.unique' => 'SKU has already been taken.',

            'variants.*.m_p_n.required' => 'MPN is required.',
            'variants.*.m_p_n.string' => 'MPN must be a string.',

            'variants.*.tooltip_information.required' => 'Varaiant Additional Information is required.',

            'variants.*.packing.required' => 'Packing is required.',
            'variants.*.packing.string' => 'Packing must be a string.',

            'variants.*.unit.required' => 'Unit is required.',
            'variants.*.unit.string' => 'Unit must be a string.',

            'variants.*.quantity.required' => 'Quantity is required.',
            'variants.*.quantity.integer' => 'Quantity must be an integer.',

            'variants.*.price_per_unit.required' => 'Actual Price/Unit is required.',
            'variants.*.price_per_unit.numeric' => 'Actual Price/Unit must be a number.',

            'variants.*.selling_price_per_unit.required' => 'Selling Price/Unit is required.',
            'variants.*.selling_price_per_unit.numeric' => 'Selling Price/Unit must be a number.',

            'variants.*.actual_weight.required' => 'Actual Weight is required.',
            'variants.*.actual_weight.numeric' => 'Actual Weight must be a number.',

            'variants.*.shipping_weight.required' => 'Shipping Weight is required.',
            'variants.*.shipping_weight.numeric' => 'Shipping Weight must be a number.',

            'variants.*.shipping_chargeable_weight.required' => 'Shipping Chargeable Weight is required.',
            'variants.*.shipping_chargeable_weight.numeric' => 'Shipping Chargeable Weight must be a number.',

            'variants.*.description.required' => 'Description is required.',
            'variants.*.description.string' => 'Description must be a string.',
        ];
    }
}
