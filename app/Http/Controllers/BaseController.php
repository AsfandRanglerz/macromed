<?php

namespace App\Http\Controllers;

use App\Traits\CountryApiRequestTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

abstract class BaseController extends Controller
{
    use CountryApiRequestTrait;
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
        $entity = $request->draft_id
            ? $this->model::find($request->draft_id)
            : new $this->model();
        $entity->fill($request->only($this->keys));
        $entity->is_draft = 0;
        $entity->status = '0';
        if ($request->hasFile('image')) {
            $oldImagePath = $entity->image;
            if ($entity->image &&  File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('admin/assets/images/brands'), $filename);
            $entity->image = 'public/admin/assets/images/brands/' . $filename;
        }
        $entity->save();

        return response()->json([
            'message' => 'Draft autosaved successfully',
            'draft_id' => $entity->id,
        ]);
    }

    public function createEntity(Request $request)
    {
        $this->validateRequest($request);
        $entity = $this->model::findOrFail($request->draft_id) ?? new $this->model();
        $entity->fill($request->only($this->keys));
        $entity->is_draft = 1;
        $entity->status = '1';
        if ($request->hasFile('image')) {
            $oldImagePath = $entity->image;
            if ($entity->image &&  File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('admin/assets/images/brands'), $filename);
            $entity->image = 'public/admin/assets/images/brands/' . $filename;
        }
        $entity->save();

        return response()->json(['alert' => 'success', 'message' => 'Entity Created Successfully!']);
    }

    public function deleteEntity($id)
    {
        $entity = $this->model::findOrFail($id);
        $imagePath = $entity->image;
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
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

    // ############# Fetch States and City code ################
    public function fetchStates(Request $request)
    {
        $countryCode = $request->input('country_code');
        $url = 'https://api.countrystatecity.in/v1/countries/' . $countryCode . '/states';

        $states = $this->fetchApiData($url);

        if (isset($states['error'])) {
            return response()->json(['error' => $states['error']], 500);
        }

        return response()->json($states);
    }
    public function fetchCities(Request $request)
    {
        $stateCode = $request->input('state_code');
        $countryCode = $request->input('country_code');
        $url = 'https://api.countrystatecity.in/v1/countries/' . $countryCode . '/states/' . $stateCode . '/cities';

        $cities = $this->fetchApiData($url);

        if (isset($cities['error'])) {
            return response()->json(['error' => $cities['error']], 500);
        }

        return response()->json($cities);
    }
}
