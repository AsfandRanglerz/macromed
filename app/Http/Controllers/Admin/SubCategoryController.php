<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    public function subCategoryData()
    {
        $subCategories = SubCategory::with('category')->latest()->get();
        $json_data["data"] =  $subCategories;
        return json_encode($json_data);
    }

    public function subCategoryIndex()
    {
        $categories = Category::all()->where('status', '1');
        $subCategories = SubCategory::with('category')->latest()->get();
        return view('admin.subcategory.index', compact('subCategories', 'categories'));
    }
    public function subCategoryCreate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('sub_categories')
                ],
                'category_id' => 'required|exists:categories,id',

            ], [
                'category_id.required' => 'Select Category it is required',
                'category_id.exists' => 'The selected category is invalid.'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $subCategory = new SubCategory($request->only(['name', 'category_id', 'slug', 'status']));
            $subCategory->save();
            return response()->json(['alert' => 'success', 'message' => 'SubCategory Created Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while Creating SubCategory!' . $e->getMessage()], 500);
        }
    }

    public function showSubCategory($id)
    {
        $subCategories = SubCategory::find($id);
        if (!$subCategories) {
            return response()->json(['alert' => 'error', 'message' => 'Sub Category Not Found'], 500);
        }
        return response()->json($subCategories);
    }
    public function updateSubCategory(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sub_categories')->ignore($id)
            ],
            'category_id' => 'required|exists:categories,id',
        ], [
            'category_id.required' => 'Select Category it is required',
            'category_id.exists' => 'The selected category is invalid.'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $subCategories = SubCategory::findOrFail($id);
            $subCategories->fill($request->only(['name', 'category_id', 'slug', 'status']));
            $subCategories->save();
            return response()->json(['alert' => 'success', 'message' => 'Sub Category Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating Sub Category' . $e->getMessage()], 500);
        }
    }

    public function deleteSubCategory($id)
    {
        $subCategories = SubCategory::findOrFail($id);
        $subCategories->delete();
        return response()->json(['alert' => 'success', 'message' => 'Sub Category Deleted SuccessFully!']);
    }
    public function updateSubCategoryStatus(Request $request, $id)
    {
        try {
            $category = SubCategory::findOrFail($id);
            if ($category->status == '0') {
                $category->status = '1';
                $message = 'Sub Category Active Successfully';
            } else if ($category->status == '1') {
                $category->status = '0';
                $message = 'Sub Category In Active Successfully';
            } else {
                return response()->json(['alert' => 'info', 'error' => 'User status is already updated or cannot be updated.']);
            }
            $category->save();
            return response()->json(['alert' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'error' => 'An error occurred while updating user status.']);
        }
    }
}
