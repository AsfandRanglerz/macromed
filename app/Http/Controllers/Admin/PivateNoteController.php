<?php

namespace App\Http\Controllers\Admin;

use App\Models\AdminNotes;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PivateNoteController extends Controller
{
    public function privateNotesData()
    {
        $privateNotes = AdminNotes::latest()->get();
        $json_data["data"] = $privateNotes;
        return json_encode($json_data);
    }

    public function privateNotesIndex()
    {
        $privateNotes = AdminNotes::all();
        return view('admin.privatenotes.index', compact('privateNotes'));
    }
    public function PrivateNotesCreate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $privateNote = new AdminNotes($request->only(['admin_id', 'sub_admin_id', 'title', 'description']));
            $privateNote->save();
            return response()->json(['alert' => 'success', 'message' => 'Private Notes Created Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while Creating PrivateNotes!' . $e->getMessage()], 500);
        }
    }

    public function showPrivateNotes($id)
    {
        $privateNote = AdminNotes::find($id);
        if (!$privateNote) {
            return response()->json(['alert' => 'error', 'message' => 'Private Notes Not Found'], 500);
        }
        return response()->json($privateNote);
    }
    public function updatePrivateNotes(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $privateNote = AdminNotes::findOrFail($id);
            $privateNote->fill($request->only(['admin_id', 'sub_admin_id', 'title', 'description']));
            $privateNote->save();
            return response()->json(['alert' => 'success', 'message' => 'Private Notes Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating Sub Admin' . $e->getMessage()], 500);
        }
    }

    public function deletePrivateNotes($id)
    {
        $privateNote = AdminNotes::findOrFail($id);
        $privateNote->delete();
        return response()->json(['alert' => 'success', 'message' => 'Private Notes Deleted SuccessFully!']);
    }
}
