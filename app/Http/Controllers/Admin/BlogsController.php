<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blogs;
use Illuminate\Http\Request;

class BlogsController extends Controller
{
    public function blogIndex()
    {
        $blogs = Blogs::first();
        return view('admin.blogs.index', compact('blogs'));
    }
    public function editIndex($id)
    {
        $blogs = Blogs::findOrFail($id);
        return view('admin.blogs.edit', compact('blogs'));
    }
    public function blogSave(Request $request, $id)
    {
        Blogs::find($id)->update(['description' => $request->description]);
        return redirect()->route('blogs.index')->with(['status' => true, 'message' => 'Update Sucessfully']);
    }
}
