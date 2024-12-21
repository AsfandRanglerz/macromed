<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CareerSection;
use Illuminate\Http\Request;

class CareerSectionController extends Controller
{
    public function sectionIndex()
    {
        $careerSections = CareerSection::first();
        return view('admin.careersection.index', compact('careerSections'));
    }
    public function sectionEditIndex($id)
    {
        $careerSections = CareerSection::findOrFail($id);
        return view('admin.careersection.edit', compact('careerSections'));
    }
    public function sectionSave(Request $request, $id)
    {
        CareerSection::find($id)->update(['description' => $request->description]);
        return redirect()->route('careersection.index')->with(['status' => true, 'message' => 'Update Sucessfully']);
    }
}
