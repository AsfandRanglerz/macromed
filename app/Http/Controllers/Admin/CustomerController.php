<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use App\Mail\userBlocked;
use App\Mail\userUnBlocked;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Mail\SalesAgentRegistration;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function customerData()
    {
        $customers = User::where('user_type', 'customer')->latest()->get();
        $json_data["data"] = $customers;
        return json_encode($json_data);
    }
    public function customerIndex()
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
        $customers = User::where('user_type', 'customer')->latest()->get();
        return view('admin.customer.index', compact('customers', 'countries'));
    }
    public function fetchCutomerStates(Request $request)
    {
        $countryCode = $request->input('country_code');

        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.countrystatecity.in/v1/countries/' . $countryCode . '/states',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => array(
                    'X-CSCAPI-KEY: TExJVmdYa1pFcWFsRWViS0c3dDRRdTdFV3hnWXJveFhQaHoyWVo3Mw=='
                ),
            ));
            $response = curl_exec($curl);

            if ($response === false) {
                throw new Exception('Error occurred while fetching states: ' . curl_error($curl));
            }

            curl_close($curl);
            $states = json_decode($response);

            return response()->json($states);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function fetchCustomerCities(Request $request)
    {
        $stateCode = $request->input('state_code');
        $countryCode = $request->input('country_code');
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.countrystatecity.in/v1/countries/' . $countryCode . '/states/' . $stateCode . '/cities',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => array(
                    'X-CSCAPI-KEY: TExJVmdYa1pFcWFsRWViS0c3dDRRdTdFV3hnWXJveFhQaHoyWVo3Mw=='
                ),
            ));
            $response = curl_exec($curl);

            if ($response === false) {
                throw new Exception('Error occurred while fetching cities: ' . curl_error($curl));
            }

            curl_close($curl);
            $cities = json_decode($response);

            return response()->json($cities);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function customerProfile($id)
    {
        $customer = User::findOrFail($id);
        return view('admin.salesagent.profile', compact('customer'));
    }
    public function customerCreate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users|max:255',
                'password' => 'required|string|min:8|max:255',
                'phone' => 'required|numeric|unique:users|min:11',
                'country' => 'required',
                'state' => 'required',
                'city' => 'required',
                'location' => 'required',
                'confirmpassword' => 'required|same:password',
                'image' => 'nullable|image|mimes:jpeg,jpg,png|max:1048',
                'profession' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $data = $request->only(['name', 'email', 'phone', 'status', 'country', 'state', 'city', 'location', 'profession']);
            $data['user_type'] = 'customer';
            $data['password'] = bcrypt($request->input('password'));
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('admin/assets/images/users'), $filename);
                $data['image'] = 'public/admin/assets/images/users/' . $filename;
            }
            $customer = User::create($data);
            if ($customer) {
                //#########Create Account ###########
                //######### Wallet  ###########

                //######### Send Mail  ###########
                $data['subadminname'] = $customer->name;
                $data['subadminemail'] = $customer->email;
                $data['password'] = $request->password;
                $data['account_name'] = $customer->name;
                $data['account_holder_name'] = $customer->email;
                $data['account_number'] = $request->password;
                Mail::to($customer->email)->send(new SalesAgentRegistration($data));
                return response()->json(['alert' => 'success', 'message' => 'Customers Created Successfully!']);
            }
            return response()->json(['alert' => 'error', 'message' => 'Customers Not Created!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while Creating Customers: ' . $e->getMessage()], 500);
        }
    }
    public function showCustomer($id)
    {
        $customer = User::find($id);
        if (!$customer) {
            return response()->json(['alert' => 'error', 'message' => 'Customer Not Found'], 500);
        }
        return response()->json($customer);
    }
    public function updateCustomer(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|numeric|min:11,' . $id,
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:1048',
            'profession' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $customer = User::findOrFail($id);
            $customer->fill($request->only(['name', 'email', 'phone', 'status', 'country', 'state', 'city', 'location', 'profession']));

            if ($request->hasFile('image')) {
                // Delete old image if exists
                $oldImagePath =  $customer->image;
                // Delete old image if it exists
                if ($customer->image &&  File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('admin/assets/images/users'), $filename);
                $customer->image = 'public/admin/assets/images/users/' . $filename;
            }
            $customer->save();

            return response()->json(['alert' => 'success', 'message' => 'Customers Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating Sub Admin: ' . $e->getMessage()], 500);
        }
    }
    public function deleteCustomer($id)
    {
        $customer = User::findOrFail($id);
        $imagePath = $customer->image;
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
        $customer->delete();
        return response()->json(['alert' => 'success', 'message' => 'Customers Deleted SuccessFully!']);
    }
    // ################ Block Sub Admin########
    public function updateCustomerBlockStatus(Request $request, $id)
    {
        try {
            $customer = User::findOrFail($id);
            if ($customer->status == '0') {
                $customer->status = '1';
                $data['username'] =  $customer->name;
                $data['useremail'] =  $customer->email;
                Mail::to($customer->email)->send(new userUnBlocked($data));
                $message = 'Customer Active Successfully';
            } else if ($customer->status == '1') {
                $customer->status = '0';
                $data['username'] =  $customer->name;
                $data['useremail'] =  $customer->email;
                $data['reason'] = $request->reason;
                Mail::to($customer->email)->send(new userBlocked($data));
                $message = 'Customer In Active Successfully';
            } else {
                return response()->json(['alert' => 'info', 'error' => 'User status is already updated or cannot be updated.']);
            }
            $customer->save();
            return response()->json(['alert' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating user status: ' . $e->getMessage()]);
        }
    }
}
