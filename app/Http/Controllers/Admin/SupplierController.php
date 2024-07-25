<?php

namespace App\Http\Controllers\Admin;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    // Genarte Supplier Id
    private function generateUniqueSupplierId()
    {
        do {
            $supplier_id = $this->generateRandomSupplierId();
        } while (Supplier::where('supplier_id', $supplier_id)->exists());

        return $supplier_id;
    }

    private function generateRandomSupplierId($length = 8)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $supplier_id = '';

        for ($i = 0; $i < $length; $i++) {
            $supplier_id .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $supplier_id;
    }
    // Code end
    public function supplierData()
    {
        $suppliers = Supplier::latest()->get();
        $json_data["data"] = $suppliers;
        return json_encode($json_data);
    }

    public function supplierIndex()
    {
        $suppliers = Supplier::all();
        return view('admin.supplier.index', compact('suppliers'));
    }
    public function supplierCreate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('suppliers')
                ],
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $supplier = new Supplier($request->only(['name', 'status']));
            $supplier->supplier_id = $this->generateUniqueSupplierId();
            $supplier->save();
            return response()->json(['alert' => 'success', 'message' => 'Number Of Uses Created Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while Creating Suppliers!' . $e->getMessage()], 500);
        }
    }

    public function showSupplier($id)
    {
        $supplier = Supplier::find($id);
        if (!$supplier) {
            return response()->json(['alert' => 'error', 'message' => 'Suppliers Not Found'], 500);
        }
        return response()->json($supplier);
    }
    public function updateSupplier(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('suppliers')->ignore($id),

            ],
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $supplier = Supplier::findOrFail($id);
            $supplier->fill($request->only(['name', 'status']));
            $supplier->save();
            return response()->json(['alert' => 'success', 'message' => 'Suppliers Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating Sub Admin' . $e->getMessage()], 500);
        }
    }

    public function deleteSupplier($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        return response()->json(['alert' => 'success', 'message' => 'Suppliers Deleted SuccessFully!']);
    }
    public function updateSupplierStatus($id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            if ($supplier->status == '0') {
                $supplier->status = '1';
                $message = 'Supplier Active Successfully';
            } else if ($supplier->status == '1') {
                $supplier->status = '0';
                $message = 'Supplier In Active Successfully';
            } else {
                return response()->json(['alert' => 'info', 'error' => 'User status is already updated or cannot be updated.']);
            }
            $supplier->save();
            return response()->json(['alert' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'error' => 'An error occurred while updating user status.']);
        }
    }
}
