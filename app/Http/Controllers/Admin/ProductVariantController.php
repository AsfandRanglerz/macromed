<?php

namespace App\Http\Controllers\Admin;

use App\Models\Unit;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductVaraint;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductVariantStore;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProductVariantController extends Controller
{
    public function getProductVariants(Request $request, $id)
    {
        $is_draft = $request->query('is_draft', '1');
        $variants = ProductVaraint::where('product_id', $id)->where('is_draft', $is_draft)->get();
        $json_data["data"] = $variants;
        return json_encode($json_data);
    }
    public function productVariantViewIndex($id)
    {
        $units = Unit::activeAndNonDraft()->get();
        $productVariant = ProductVaraint::where('product_id', $id)->get();
        $product = Product::findOrFail($id);
        return view('admin.product.product_variants_index', compact('productVariant', 'id', 'units', 'product'));
    }
    public function productVariantIndex($id)
    {
        $units = Unit::activeAndNonDraft()->get();
        $data = Product::activeAndNonDraft()->with('productVaraint')->find($id);
        return view('admin.product.product_variant_create', compact('data', 'units'));
    }

    public function productVariantStore(ProductVariantStore $request, $productId)
    {
        try {
            foreach ($request->variants as $variant) {

                if (isset($variant['image']) && $variant['image']->isValid()) {
                    $filename = time() . '_' . uniqid() . '.' . $variant['image']->getClientOriginalExtension();

                    // Move the file to the correct directory
                    $variant['image']->move(public_path('admin/assets/images/products'), $filename);

                    // Store only the filename, not the full path
                    // $productVariant->update(['image' => $filename]);
                }

                $productVariant = ProductVaraint::updateOrCreate(
                    ['id' => $variant['id'] ?? null],
                    [
                        'product_id' => $productId,
                        'm_p_n' => $variant['m_p_n'],
                        's_k_u' => $variant['s_k_u'],
                        'packing' => $variant['packing'],
                        'unit' => $variant['unit'],
                        'quantity' => $variant['quantity'],
                        'remaining_quantity' => $variant['quantity'],
                        'price_per_unit' => $variant['price_per_unit'],
                        'selling_price_per_unit' => $variant['selling_price_per_unit'],
                        'actual_weight' => $variant['actual_weight'],
                        'volumetric_weight' => $variant['volumetric_weight'],
                        'shipping_chargeable_weight' => $variant['shipping_chargeable_weight'],
                        'status' => $variant['status'],
                        'description' => $variant['description'],
                        'tooltip_information' => $variant['tooltip_information'],
                        'image' => $filename,
                        'is_draft' => 1,
                    ]
                );
                // if ($request->hasFile('image')) {
                //     $imageFiles = $request->file('image');

                //     // If there's only one image, convert it to an array
                //     if (!is_array($imageFiles)) {
                //         $imageFiles = [$imageFiles];
                //     }

                //     // $image->move(public_path('admin/assets/images/products'), $filename);

                //     // // Update the existing product variant with the image path
                //     // $productVariant->update([
                //     //     'image' => 'admin/assets/images/products/' . $filename, // Remove 'public/' prefix
                //     // ]);
                //     foreach ($imageFiles as $image) {
                //         $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                //         $image->move(public_path('admin/assets/images/products'), $filename);
                //         $relativePath = 'admin/assets/images/products/' . $filename;
                //         $productVariant->update(['image' => $filename,]);
                //     }
                // }




            }
            // foreach ($request->variants as  $variant) {
            //     if (isset($variant['image']) && $variant['image']->isValid()) {
            //         $filename = time() . '_' . uniqid() . '.' . $variant['image']->getClientOriginalExtension();

            //         // Move the file to the correct directory
            //         $variant['image']->move(public_path('admin/assets/images/products'), $filename);

            //         // Store only the filename, not the full path
            //         $productVariant->update(['image' => $filename]);
            //     }
            // }
            return response()->json([
                'success' => true,
                'message' => 'Product variants saved successfully!',
                'redirectUrl' => route('product_variant_index.index', ['id' => $productId]),
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to save product variants. Please try again later.',
            ], 500);
        }
    }

    // public function productVariantAutoStore(Request $request, $productId)
    // {
    //     // return $request;
    //     try {
    //         foreach ($request->variants as $variant) {

    //             ProductVaraint::updateOrCreate(
    //                 ['id' => $variant['id'] ?? null],
    //                 [
    //                     'product_id' => $productId,
    //                     'm_p_n' => $variant['m_p_n'],
    //                     's_k_u' => $variant['s_k_u'],
    //                     'packing' => $variant['packing'],
    //                     'unit' => $variant['unit'],
    //                     'quantity' => $variant['quantity'],
    //                     'remaining_quantity' => $variant['quantity'],
    //                     'price_per_unit' => $variant['price_per_unit'],
    //                     'selling_price_per_unit' => $variant['selling_price_per_unit'],
    //                     'actual_weight' => $variant['actual_weight'],
    //                     'volumetric_weight' => $variant['volumetric_weight'],
    //                     'shipping_chargeable_weight' => $variant['shipping_chargeable_weight'],
    //                     'status' => $variant['status'],
    //                     'description' => $variant['description'],
    //                     'tooltip_information' => $variant['tooltip_information'],
    //                     'is_draft' => 0,
    //                 ]
    //             );
    //         }
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Product variants saved successfully!',
    //             'redirectUrl' => route('product_variant_index.index', ['id' => $productId]),
    //         ]);
    //     } catch (ValidationException $e) {
    //         return response()->json([
    //             'success' => false,
    //             'errors' => $e->errors(),
    //         ], 422);
    //     } catch (\Exception $e) {

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to save product variants. Please try again later.',
    //         ], 500);
    //     }
    // }

    public function showVariants($id)
    {
        $productVariant  = ProductVaraint::find($id);
        if (!$productVariant) {
            return response()->json(['alert' => 'error', 'message' => 'Models Not Found'], 500);
        }
        return response()->json($productVariant);
    }
    public function updateVariant(Request $request, $id)
    {
        try {
            $product = ProductVaraint::findOrFail($id);
            $validator = Validator::make(
                $request->all(),
                [
                    'm_p_n' => 'required',
                    's_k_u' => [
                        'required',
                        'string',
                        'max:255',
                        Rule::unique('product_varaints')->ignore($id)
                    ],
                    // 'tooltip_information' => 'required',
                    'tooltip_information' => [
                        'required',
                        function ($attribute, $value, $fail) {
                            // Count words (split by spaces, tabs, and newlines)
                            $wordCount = count(preg_split('/\s+/', trim($value), -1, PREG_SPLIT_NO_EMPTY));
                            if ($wordCount > 150) {
                                $fail('The variant information cannot exceed 150 words.');
                            }
                        }
                    ],
                    'packing' => 'required',
                    'unit' => 'required',
                    'quantity' => [
                        'required',
                        'numeric',
                        function ($attribute, $value, $fail) use ($product) {
                            if ($value < $product->quantity) {
                                $fail('The new quantity must be greater than the previous quantity (' . $product->quantity . ').');
                            }
                        }
                    ],
                    'price_per_unit' => 'required|numeric',
                    // 'selling_price_per_unit' => 'required|numeric',
                    'actual_weight' => 'required|numeric',
                    'volumetric_weight' => 'required|numeric',
                    'selling_price_per_unit' => [
                        'required',
                        'numeric',
                        function ($attribute, $value, $fail) use ($request) {
                            if ($value <= $request->input('price_per_unit')) {
                                $fail('The Selling Price/Unit must be greater than the Actual Price/Unit.');
                            }
                        },
                    ],
                    'shipping_chargeable_weight' => [
                        'required',
                        'numeric',
                        function ($attribute, $value, $fail) use ($request) {
                            $actualWeight = $request->input('actual_weight');
                            $volumetricWeight = $request->input('volumetric_weight');

                            if ($value <= $actualWeight || $value <= $volumetricWeight) {
                                $fail('The Shipping Chargeable Weight must be greater than the Actual Weight and Volumetric Weight.');
                            }
                        },
                    ],
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                ],
                [
                    'image.required' => 'Tool Tip image is required.',
                    'image.max' => 'Tool Tip image size should not be more than 2MB .'
                ],

                [
                    'm_p_n.required' => 'MPN is required.',
                ]

            );

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $quantityDifference = 0;
            if ($request->quantity ==  $product->quantity) {
                $quantityDifference = 0;
            }
            $quantityDifference = $request->quantity - $product->quantity;
            $product->remaining_quantity += $quantityDifference;
            // Image upload logic
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('admin/assets/images/products'), $filename);

                // Store only the filename in the database
                $product->image = $filename;
            }

            $product->fill($request->only([
                'm_p_n',
                's_k_u',
                'packing',
                'unit',
                'quantity',
                'price_per_unit',
                'selling_price_per_unit',
                'actual_weight',
                'volumetric_weight',
                'shipping_chargeable_weight',
                'status',
                'description',
                'tooltip_information',
            ]));

            $product->save();
            return response()->json(['alert' => 'success', 'message' => 'Product Variant Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while Updating Product Variant! ' . $e->getMessage()], 500);
        }
    }

    public function updateVariantsStatus($id)
    {
        try {
            $productVariant = ProductVaraint::findOrFail($id);
            if ($productVariant->status == '0') {
                $productVariant->status = '1';
                $message = 'Product Variant Active Successfully';
            } else if ($productVariant->status == '1') {
                $productVariant->status = '0';
                $message = 'Product Variant In Active Successfully';
            } else {
                return response()->json(['alert' => 'info', 'error' => 'User status is already updated or cannot be updated.']);
            }
            $productVariant->save();
            return response()->json(['alert' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'error' => 'An error occurred while updating user status.']);
        }
    }
    public function deleteProductVariant($id)
    {
        $productVariant = ProductVaraint::findOrFail($id);
        $productVariant->delete();
        return response()->json(['alert' => 'success', 'message' => 'Product Variant Deleted SuccessFully!']);
    }
}
