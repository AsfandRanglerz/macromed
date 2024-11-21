<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Http\Requests\SubCategoryRequest;


class SubCategoryController extends BaseController
{
    protected $model = SubCategory::class;
    protected $keys = ['name', 'category_id', 'slug', 'status', 'is_draft'];
    protected $formRequestClass = SubCategoryRequest::class;


    public function subCategoryData(Request $request)
    {
        try {
            $is_draft = $request->query('is_draft', '1');
            $subCategories = $this->model::with('category')->where('is_draft', $is_draft)->latest()->get();
            return response()->json(['data' => $subCategories]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch category data',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function subCategoryIndex()
    {
        $categories = Category::all()->where('status', '1');
        $subCategories = SubCategory::with('category')->latest()->get();
        return view('admin.subcategory.index', compact('subCategories', 'categories'));
    }

    public function showSubCategory($id)
    {
        $subCategories = $this->model::findOrFail($id);
        if (!$subCategories) {
            return response()->json(['alert' => 'error', 'message' => 'Sub Category Not Found'], 500);
        }
        return response()->json($subCategories);
    }
}
