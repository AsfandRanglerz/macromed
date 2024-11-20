<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Category;
use App\Models\SubCategory;
use App\Http\Requests\SubCategoryRequest;


class SubCategoryController extends BaseController
{
    protected $model = SubCategory::class;
    protected $keys = ['name', 'category_id', 'slug', 'status', 'is_draft'];
    protected $formRequestClass = SubCategoryRequest::class;


    public function subCategoryData()
    {
        $subCategories = $this->model::where('is_draft', '1')->latest()->get();
        $json_data["data"] =  $subCategories;
        return json_encode($json_data);
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
