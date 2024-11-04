<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function categoryData()
    {
        $categorys = Category::latest()->get();
        $json_data["data"] = $categorys;
        return json_encode($json_data);
    }

    public function categoryIndex()
    {
        // $categories = Category::with('discounts')all();
        return view('admin.category.index');
    }
    public function categoryCreate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('categories')
                ],
                'slug' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('categories')
                ],
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $category = new Category($request->only(['name', 'slug', 'status']));
            $category->save();
            return response()->json(['alert' => 'success', 'message' => 'Category Created Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while Creating Category!' . $e->getMessage()], 500);
        }
    }

    public function showCategory($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['alert' => 'error', 'message' => 'Category Not Found'], 500);
        }
        return response()->json($category);
    }
    public function updateCategory(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($id),
                'slug' => 'required'
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($id),
                'slug' => 'required'
            ],
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $category = Category::findOrFail($id);
            $category->fill($request->only(['name', 'slug', 'status']));
            $category->save();
            return response()->json(['alert' => 'success', 'message' => 'Category Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating Sub Admin' . $e->getMessage()], 500);
        }
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['alert' => 'success', 'message' => 'Category Deleted SuccessFully!']);
    }
    public function updateCategoryStatus(Request $request, $id)
    {
        try {
            $category = Category::findOrFail($id);
            if ($category->status == '0') {
                $category->status = '1';
                $message = 'Category Active Successfully';
            } else if ($category->status == '1') {
                $category->status = '0';
                $message = 'Category In Active Successfully';
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
