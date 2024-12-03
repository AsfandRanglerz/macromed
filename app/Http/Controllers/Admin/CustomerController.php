<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use App\Mail\userBlocked;
use App\Mail\userUnBlocked;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Mail\SalesAgentRegistration;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\CreateCustomer;
use App\Http\Requests\UpdateCustomer;
use App\Traits\CountryApiRequestTrait;


class CustomerController extends Controller
{
    use CountryApiRequestTrait;
    public function customerData(Request $request)
    {
        try {
            $is_draft = $request->query('is_draft', '1');
            $customers = User::where('user_type', 'customer')->where('is_draft', $is_draft)->latest()->get();
            return response()->json(['data' => $customers], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch category data',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function customerIndex()
    {
        $countries = $this->fetchApiData('https://api.countrystatecity.in/v1/countries');
        if (isset($countries['error'])) {
            $countries = [];
        }
        $customers = User::where('user_type', 'customer')->latest()->get();
        return view('admin.customer.index', compact('customers', 'countries'));
    }
    public function fetchCutomerStates(Request $request)
    {

        try {
            $countryCode = $request->input('country_code');
            $url = 'https://api.countrystatecity.in/v1/countries/' . $countryCode . '/states';

            $states = $this->fetchApiData($url);

            if (isset($states['error'])) {
                return response()->json(['error' => $states['error']], 500);
            }

            return response()->json($states);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function fetchCustomerCities(Request $request)
    {

        try {
            $stateCode = $request->input('state_code');
            $countryCode = $request->input('country_code');
            $url = 'https://api.countrystatecity.in/v1/countries/' . $countryCode . '/states/' . $stateCode . '/cities';

            $cities = $this->fetchApiData($url);

            if (isset($cities['error'])) {
                return response()->json(['error' => $cities['error']], 500);
            }

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
    public function customerAutoSave(Request $request)
    {
        try {
            $customers = $request->draft_id
                ? User::find($request->draft_id)
                : new User();
            $customers->fill($request->only(['name', 'email', 'phone', 'status', 'country', 'state', 'city', 'location', 'profession', 'work_space_name', 'work_space_email', 'work_space_address', 'work_space_number']));
            $customers->user_type = 'customer';
            $customers->is_draft = 0;
            $customers->status = '0';
            if ($request->hasFile('image')) {
                $oldImagePath = $customers->image;
                if ($customers->image && File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('admin/assets/images/users'), $filename);
                $customers->image = 'public/admin/assets/images/users/' . $filename;
            }
            $customers->save();
            return response()->json([
                'alert' => 'success',
                'message' => 'Customer Created Successfully!',
                'draft_id' => $customers->id,
            ]);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while Creating Customers: ' . $e->getMessage()], 500);
        }
    }
    public function customerCreate(UpdateCustomer $request)
    {
        try {
            DB::beginTransaction();
            $customers = $request->draft_id
                ? User::findOrFail($request->draft_id)
                : new User();
            $customers->fill($request->only(['name', 'email', 'phone', 'status', 'country', 'state', 'city', 'location', 'profession', 'work_space_name', 'work_space_email', 'work_space_address', 'work_space_number']));
            $customers->user_type = 'customer';
            $customers->is_draft = 1;
            $customers->status = '1';
            if ($request->hasFile('image')) {
                $oldImagePath = $customers->image;
                if ($customers->image && File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('admin/assets/images/users'), $filename);
                $customers->image = 'public/admin/assets/images/users/' . $filename;
            }
            $customers->save();
            if ($customers->password == null) {
                $generatedPassword = Str::random(8);
                $customers->password = bcrypt($generatedPassword);
                $customers->save();
                $data['subadminname'] = $customers->name;
                $data['subadminemail'] = $customers->email;
                $data['password'] = $request->password;
                $data['account_name'] = $customers->name;
                $data['account_holder_name'] = $customers->email;
                $data['account_number'] = $request->password;
                Mail::to($customers->email)->send(new SalesAgentRegistration($data));
                DB::commit();
                return response()->json(['alert' => 'success', 'message' => 'Customers Created Successfully!']);
            } else {
                DB::commit();
                return response()->json(['alert' => 'success', 'message' => 'Customer Updated Successfully!']);
            }
            return response()->json(['alert' => 'error', 'message' => 'Customers Not Created!']);
        } catch (\Exception $e) {
            DB::rollBack();
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
                $customer->is_active = 0;
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
