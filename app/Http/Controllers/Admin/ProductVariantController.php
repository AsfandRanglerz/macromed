<?php

namespace App\Http\Controllers\Admin;

use App\Models\Unit;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductVaraint;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProductVariantController extends Controller
{
    public function getProductVariants($id)
    {
        $variants = ProductVaraint::where('product_id', $id)->get();
        $json_data["data"] = $variants;
        return json_encode($json_data);
    }
    public function productVariantViewIndex($id)
    {
        $units = Unit::all();
        $productVariant = ProductVaraint::where('product_id', $id)->get();
        $product = Product::findOrFail($id);
        return view('admin.product.product_variants_index', compact('productVariant', 'id', 'units', 'product'));
    }
    public function productVariantIndex($id)
    {
        $units = Unit::all();
        $data = Product::where('status', '1')->with('productVaraint')->find($id);
        return view('admin.product.product_variant_create', compact('data', 'units'));
    }
    public function productVariantStore(Request $request, $productId)
    {
        try {
            $request->validate([
                'variants.*.m_p_n' => 'required|string',
                'variants.*.s_k_u' => 'required|string',
                'variants.*.packing' => 'required|string',
                'variants.*.unit' => 'required|string',
                'variants.*.quantity' => 'required|integer',
                'variants.*.price_per_unit' => 'required|numeric',
                'variants.*.selling_price_per_unit' => 'required|numeric',
                'variants.*.actual_weight' => 'required|numeric',
                'variants.*.shipping_weight' => 'required|numeric',
                'variants.*.shipping_chargeable_weight' => 'required|numeric',
                'variants.*.description' => 'required|string',
            ], [
                'variants.*.s_k_u.required' => 'SKU is required.',
                'variants.*.s_k_u.string' => 'SKU must be a string.',

                'variants.*.m_p_n.required' => 'MPN is required.',
                'variants.*.m_p_n.string' => 'MPN must be a string.',

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
            ]);


            foreach ($request->variants as $variant) {
                ProductVaraint::updateOrCreate(
                    ['id' => $variant['id'] ?? null],
                    [
                        'product_id' => $productId,
                        'm_p_n' => $variant['m_p_n'],
                        's_k_u' => $variant['s_k_u'],
                        'packing' => $variant['packing'],
                        'unit' => $variant['unit'],
                        'quantity' => $variant['quantity'],
                        'price_per_unit' => $variant['price_per_unit'],
                        'selling_price_per_unit' => $variant['selling_price_per_unit'],
                        'actual_weight' => $variant['actual_weight'],
                        'shipping_weight' => $variant['shipping_weight'],
                        'shipping_chargeable_weight' => $variant['shipping_chargeable_weight'],
                        'status' => $variant['status'],
                        'description' => $variant['description'],
                    ]
                );
            }
            return redirect()->route('product_variant_index.index', ['id' => $productId])
                ->with('message', 'Product variants saved successfully!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            return redirect()->back()->with('error', 'Failed to save product variants. Please try again later.');
        }
    }
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
            $validator = Validator::make($request->all(), [
                'm_p_n' => 'required',
                's_k_u' => 'required',
                'packing' => 'required',
                'unit' => 'required',
                'quantity' => 'required|numeric',
                'price_per_unit' => 'required|numeric',
                'selling_price_per_unit' => 'required|numeric',
                'actual_weight' => 'required|numeric',
                'shipping_weight' => 'required|numeric',
                'shipping_chargeable_weight' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $product = ProductVaraint::findOrFail($id);
            $product->fill($request->only([
                'm_p_n', 's_k_u', 'packing', 'unit', 'quantity', 'price_per_unit', 'selling_price_per_unit',
                'actual_weight', 'shipping_weight', 'shipping_chargeable_weight', 'status', 'description'
            ]));
            $product->save();
            return response()->json(['alert' => 'success', 'message' => 'Product Variant Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while Updating Product Variant!' . $e->getMessage()], 500);
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
