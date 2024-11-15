<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Traits\ChecksUserTypeTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategory;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    use ChecksUserTypeTrait;
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
    public function getDraft()
    {
        $user = $this->checkUserType();
        $draft = Category::where('admin_user_type', get_class($user))
            ->where('admin_user_id', $user->id)
            ->where('is_draft', false)
            ->first();

        return response()->json(['data' => $draft]);
    }
    public function saveDraft(CreateCategory $request)
    {
        $user = $this->checkUserType();
        $draft = Category::firstOrNew(
            [
                'admin_user_type' => get_class($user),
                'admin_user_id' => $user->id,
                'is_draft' => false
            ]
        );

        $draft->fill([
            'name' => $request->name,
            'slug' => $request->slug,
            'status' => $request->status,
        ]);

        $draft->admin_user()->associate($user);
        $draft->save();

        return response()->json(['message' => 'Draft saved successfully.']);
    }

    public function categoryCreate(Request $request, $categoryId = null)
    {
        try {
            $user = $this->checkUserType();
            $category = $categoryId ? Category::findOrFail($categoryId) : new Category();
            $category->fill([
                'name' => $request->name,
                'slug' => $request->slug,
                'status' => $request->status,
            ]);
            $category->is_draft =  true;
            $category->admin_user()->associate($user);
            $category->save();

            return response()->json(['alert' => 'success', 'message' => 'Category Created/Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()], 500);
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
