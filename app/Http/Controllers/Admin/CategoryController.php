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
        try {
            $is_draft = $request->query('is_draft', '1');
            $categories = $this->model::where('is_draft', $is_draft)->latest()->get();
            return response()->json(['data' => $categories], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch category data',
                'message' => $e->getMessage(),
            ], 500);
        }
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
