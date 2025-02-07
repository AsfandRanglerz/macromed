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
            'variants.*.selling_price_per_unit' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[1]; // Get the variant index
                    $pricePerUnit = request()->input("variants.$index.price_per_unit");

                    if ($value <= $pricePerUnit) {
                        $fail("Selling Price/Unit must be greater than Actual Price/Unit.");
                    }
                },
            ],
            'variants.*.actual_weight' => 'required|numeric',
            'variants.*.volumetric_weight' => 'required|numeric',
            'variants.*.shipping_chargeable_weight' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[1]; // Get the variant index
                    $actualWeight = request()->input("variants.$index.actual_weight");
                    $shippingWeight = request()->input("variants.$index.volumetric_weight");

                    if ($value <= $actualWeight || $value <= $shippingWeight) {
                        $fail("Shipping Chargeable Weight must be greater than Actual Weight and Volumetric Weight.");
                    }
                },
            ],
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

            'variants.*.volumetric_weight.required' => 'Volumetric Weight is required.',
            'variants.*.volumetric_weight.numeric' => 'Volumetric Weight must be a number.',

            'variants.*.shipping_chargeable_weight.required' => 'Shipping Chargeable Weight is required.',
            'variants.*.shipping_chargeable_weight.numeric' => 'Shipping Chargeable Weight must be a number.',

            'variants.*.description.required' => 'Description is required.',
            'variants.*.description.string' => 'Description must be a string.',
        ];
    }
}
