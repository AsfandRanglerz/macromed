<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brands;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class BrandsController extends Controller
{
    public function brandsData()
    {
        $brands = Brands::latest()->get();
        $json_data["data"] = $brands;
        return json_encode($json_data);
    }

    public function brandsIndex()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.countrystatecity.in/v1/countries',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'X-CSCAPI-KEY: TExJVmdYa1pFcWFsRWViS0c3dDRRdTdFV3hnWXJveFhQaHoyWVo3Mw=='
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $countries = json_decode($response);

        // Decode the JSON response
        if ($countries == NULL) {
            $countries = [];
        }
        $brands = Brands::all();
        return view('admin.brands.index', compact('brands', 'countries'));
    }
    public function brandsCreate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('brands')
                ],
                'slug' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('brands')
                ],
                'image' => 'required|image|mimes:jpeg,jpg,png|max:1048',
                'contact_detail' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $brand = new Brands($request->only(['name', 'slug', 'status', 'owner', 'company', 'company_country', 'contact_detail']));
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('admin/assets/images/brands'), $filename);
                $brand->image = 'public/admin/assets/images/brands/' . $filename;
            }
            $brand->save();
            return response()->json(['alert' => 'success', 'message' => 'Brands Created Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while Creating Brands!' . $e->getMessage()], 500);
        }
    }

    public function showBrands($id)
    {
        $brand = Brands::find($id);
        if (!$brand) {
            return response()->json(['alert' => 'error', 'message' => 'Brands Not Found'], 500);
        }
        return response()->json($brand);
    }
    public function updateBrands(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('brands')->ignore($id),

            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('brands')->ignore($id),

            ],
            'contact_detail' => 'required|numeric'

        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $brand = Brands::findOrFail($id);
            $brand->fill($request->only(['name', 'slug', 'status', 'owner', 'company', 'company_country', 'contact_detail']));
            if ($request->hasFile('image')) {
                $oldImagePath = $brand->image;
                if ($brand->image &&  File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('admin/assets/images/brands'), $filename);
                $brand->image = 'public/admin/assets/images/brands/' . $filename;
            }
            $brand->save();
            return response()->json(['alert' => 'success', 'message' => 'Brands Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating Sub Admin' . $e->getMessage()], 500);
        }
    }

    public function deleteBrands($id)
    {
        $brand = Brands::findOrFail($id);
        $imagePath = $brand->image;
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
        $brand->delete();
        return response()->json(['alert' => 'success', 'message' => 'Brands Deleted SuccessFully!']);
    }
    public function updateBrandsStatus($id)
    {
        try {
            $brand = Brands::findOrFail($id);
            if ($brand->status == '0') {
                $brand->status = '1';
                $message = 'Brands Active Successfully';
            } else if ($brand->status == '1') {
                $brand->status = '0';
                $message = 'Brands In Active Successfully';
            } else {
                return response()->json(['alert' => 'info', 'error' => 'User status is already updated or cannot be updated.']);
            }
            $brand->save();
            return response()->json(['alert' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'error' => 'An error occurred while updating user status.']);
        }
    }
}
