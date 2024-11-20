<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Http\Controllers\BaseController;
use App\Http\Requests\CreateCategory;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    protected $model = Category::class;
    protected $keys = ['name', 'slug', 'is_draft', 'status'];
    protected $formRequestClass = CreateCategory::class;

    public function categoryData(Request $request)
    {
        // $is_draft = $request->query('is_draft', 1);
        $categories = $this->model::where('is_draft', '1')->latest()->get();

        return response()->json(['data' => $categories]);
    }

    public function categoryIndex()
    {
        return view('admin.category.index');
    }

    public function showCategory($id)
    {
        $category = $this->model::findOrFail($id);

        return response()->json($category);
    }
}
