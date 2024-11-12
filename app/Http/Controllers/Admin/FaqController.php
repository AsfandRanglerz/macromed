<?php

namespace App\Http\Controllers\Admin;

use App\Models\Faq;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FAQS;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
    public function faqData()
    {
        $faqs = FAQS::orderBy('position')->get();
        $json_data["data"] = $faqs;
        return json_encode($json_data);
    }

    public function faqIndex()
    {
        $faqs = FAQS::all();
        return view('admin.faqs.index', compact('faqs'));
    }
    public function faqCreate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'questions' => 'required',
                'answers' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $faq = new FAQS($request->only(['questions', 'answers']));

            $faq->save();
            return response()->json(['alert' => 'success', 'message' => 'faq Created Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while Creating faq!' . $e->getMessage()], 500);
        }
    }

    public function showfaq($id)
    {
        $faq = FAQS::find($id);
        if (!$faq) {
            return response()->json(['alert' => 'error', 'message' => 'faq Not Found'], 500);
        }
        return response()->json($faq);
    }
    public function updatefaq(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'questions' => 'required',
            'answers' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $faq = FAQS::findOrFail($id);
            $faq->fill($request->only(['questions', 'answers']));
            $faq->save();
            return response()->json(['alert' => 'success', 'message' => 'faq Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating Sub Admin' . $e->getMessage()], 500);
        }
    }

    public function deletefaq($id)
    {
        $faq = FAQS::findOrFail($id);
        $faq->delete();
        return response()->json(['alert' => 'success', 'message' => 'faq Deleted SuccessFully!']);
    }

    public function faqReorder(Request $request)
    {


        foreach ($request->order as $item) {
            FAQS::where('id', $item['id'])->update(['position' => $item['position']]);
        }

        return response()->json(['message' => 'Order updated successfully']);
    }
}
