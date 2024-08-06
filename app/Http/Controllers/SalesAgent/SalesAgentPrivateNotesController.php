<?php

namespace App\Http\Controllers\SalesAgent;

use Illuminate\Http\Request;
use App\Models\SalesAgentNotes;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SalesAgentPrivateNotesController extends Controller
{
    public function agentNotesData()
    {
        if (auth()->guard('sales_agent')->check()) {
            $agentNotes = SalesAgentNotes::where('agent_id', auth()->guard('sales_agent')->id())->latest()->get();
            $json_data["data"] = $agentNotes;
            return json_encode($json_data);
        }
    }

    public function agentNotesIndex()
    {
        $agentNotes = SalesAgentNotes::all();
        return view('salesagent.privatenotes.index', compact('agentNotes'));
    }
    public function agentNotesCreate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $agentNotes = new SalesAgentNotes($request->only(['title', 'description']));
            if (auth()->guard('sales_agent')->check()) {
                $agentNotes->agent_id = auth()->guard('sales_agent')->id();
            }
            $agentNotes->save();
            return response()->json(['alert' => 'success', 'message' => 'Private Notes Created Successfully!']);
        } catch (\Exception $e) {

            return response()->json(['alert' => 'error', 'message' => 'An error occurred while Creating PrivateNotes! ' . $e->getMessage()], 500);
        }
    }


    public function showAgentNotes($id)
    {
        $agentNotes = SalesAgentNotes::find($id);
        if (!$agentNotes) {
            return response()->json(['alert' => 'error', 'message' => 'Private Notes Not Found'], 500);
        }
        return response()->json($agentNotes);
    }
    public function updateAgentNotes(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $agentNotes = SalesAgentNotes::findOrFail($id);
            $agentNotes->fill($request->only(['title', 'description']));
            $agentNotes->save();
            return response()->json(['alert' => 'success', 'message' => 'Private Notes Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating Private Notes! ' . $e->getMessage()], 500);
        }
    }


    public function deleteAgentNotes($id)
    {
        $agentNotes = SalesAgentNotes::findOrFail($id);
        $agentNotes->delete();
        return response()->json(['alert' => 'success', 'message' => 'Private Notes Deleted SuccessFully!']);
    }
}
