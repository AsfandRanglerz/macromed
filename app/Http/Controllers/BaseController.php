<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class BaseController extends Controller
{
    protected $model;
    protected $keys;
    protected $formRequestClass;

    protected function validateRequest(Request $request)
    {
        if ($this->formRequestClass) {
            app($this->formRequestClass);
        }
    }

    public function autosave(Request $request)
    {
        $this->validateRequest($request);

        // Check if we are updating an existing draft or creating a new one
        $entity = $request->draft_id
            ? $this->model::find($request->draft_id)
            : new $this->model();

        $entity->fill($request->only($this->keys));

        // If it's an update, we maintain the draft status as 1
        if ($entity->is_draft == 1) {
            $entity->is_draft = 1;
        } else {
            $entity->is_draft = 0; // For new drafts, mark it as a draft
        }

        $entity->save();

        return response()->json([
            'message' => 'Draft autosaved successfully',
            'draft_id' => $entity->id,
        ]);
    }

    public function fetchDraft($id)
    {
        $entity = $this->model::findOrFail($id);
        if ($entity->is_draft == '0') {
            return response()->json($entity);
        }

        return response()->json(['message' => 'Not a draft'], 400);
    }

    public function createEntity(Request $request)
    {
        $this->validateRequest($request);
        $entity = $this->model::find($request->draft_id) ?? new $this->model();
        $entity->fill($request->only($this->keys));
        $entity->is_draft = 1; // Mark as draft if it's being created
        $entity->save();

        return response()->json(['alert' => 'success', 'message' => 'Entity Created Successfully!']);
    }

    public function updateEntity(Request $request, $id)
    {
        // $this->validateRequest($request);
        $entity = $this->model::findOrFail($id);
        $entity->fill($request->only($this->keys));
        $entity->is_draft = 1; // Maintain draft status during update
        $entity->save();

        return response()->json(['alert' => 'success', 'message' => 'Entity Updated Successfully!']);
    }

    public function deleteEntity($id)
    {
        $entity = $this->model::findOrFail($id);
        $entity->delete();

        return response()->json(['alert' => 'success', 'message' => 'Entity Deleted Successfully!']);
    }

    public function updateStatus(Request $request, $id)
    {
        $entity = $this->model::findOrFail($id);
        $entity->status = $entity->status == '0' ? '1' : '0';
        $entity->save();

        $message = $entity->status == '1'
            ? 'Entity Activated Successfully'
            : 'Entity Deactivated Successfully';

        return response()->json(['alert' => 'success', 'message' => $message]);
    }
}
