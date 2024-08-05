<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Admin;
use App\Models\AdminNotes;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PivateNoteController extends Controller
{
    public function privateNotesData()
    {
        if (auth()->guard('web')->check()) {
            $privateNotes = AdminNotes::where('sub_admin_id', auth()->guard('web')->id())->latest()->get();
            $json_data["data"] = $privateNotes;
            return json_encode($json_data);
        } elseif (auth()->guard('admin')->check()) {
            $privateNotes = AdminNotes::where('admin_id', auth()->guard('admin')->id())->latest()->get();
            $json_data["data"] = $privateNotes;
            return json_encode($json_data);
        }
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
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $privateNote = new AdminNotes($request->only(['title', 'description']));
            if (auth()->guard('web')->check()) {
                $privateNote->sub_admin_id = auth()->guard('web')->id();
            } elseif (auth()->guard('admin')->check()) {
                $privateNote->admin_id = auth()->guard('admin')->id();
            }
            $privateNote->save();
            return response()->json(['alert' => 'success', 'message' => 'Private Notes Created Successfully!']);
        } catch (\Exception $e) {

            return response()->json(['alert' => 'error', 'message' => 'An error occurred while Creating PrivateNotes! ' . $e->getMessage()], 500);
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
            'description' => 'required|string', // Add validation for description
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $privateNote = AdminNotes::findOrFail($id);

            $privateNote->fill($request->only(['title', 'description']));
            if ($request->has('admin_id')) {
                $privateNote->admin_id = $request->input('admin_id');
            }
            if ($request->has('sub_admin_id')) {
                $privateNote->sub_admin_id = $request->input('sub_admin_id');
            }

            $privateNote->save();

            return response()->json(['alert' => 'success', 'message' => 'Private Notes Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating Private Notes! ' . $e->getMessage()], 500);
        }
    }


    public function deletePrivateNotes($id)
    {
        $privateNote = AdminNotes::findOrFail($id);
        $privateNote->delete();
        return response()->json(['alert' => 'success', 'message' => 'Private Notes Deleted SuccessFully!']);
    }
}
