<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Mail\userBlocked;
use App\Mail\userUnBlocked;
use Illuminate\Http\Request;
use App\Mail\subAdminRegistration;
use App\Http\Controllers\Controller;
use App\Models\SalesAgent;
use App\Models\UserAccount;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SalesAgentController extends Controller
{
    public function salesagentData()
    {
        $salesManagers = SalesAgent::where('user_type', 'salesmanager')->with('bankAccounts')->latest()->get();
        $json_data["data"] = $salesManagers;
        return json_encode($json_data);
    }
    public function salesagentIndex()
    {
        $salesManagers = SalesAgent::where('user_type', 'salesmanager')->with('bankAccounts')->latest()->get();
        // return $salesManagers;
        return view('admin.salesagent.index', compact('salesManagers'));
    }
    // public function Sales ManagersProfile($id)
    // {
    //     $salesManager = User::findOrFail($id);
    //     return view('admin.Sales Managers.Sales Managersprofile', compact('Sales Managers'));
    // }
    public function salesagentCreate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users|max:255',
                'account_number' => 'required|numeric|unique:user_accounts|min:16',
                'password' => 'required|string|min:8|max:255',
                'phone' => 'required|unique:users|min:11',
                'confirmpassword' => 'required|same:password',
                'user_type' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,jpg,png|max:1048',
                'account_name' => 'required|string|max:255',
                'account_holder_name' => 'required|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $data = $request->only(['name', 'email', 'phone', 'user_type', 'status']);
            $data['password'] = bcrypt($request->input('password'));
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('admin/assets/images/users'), $filename);
                $data['image'] = 'public/admin/assets/images/users/' . $filename;
            }
            $salesManager = SalesAgent::create($data);
            if ($salesManager) {
                //#########Create Account ###########
                $account = new UserAccount();
                $account->user_id = $salesManager->id;
                $account->account_name = $request->account_name;
                $account->account_holder_name = $request->account_holder_name;
                $account->account_number = $request->account_number;
                $account->save();
                // $data['subadminname'] = $salesManager->name;
                // $data['subadminemail'] = $salesManager->email;
                // $data['password'] = $request->password;
                // Mail::to($salesManager->email)->send(new subAdminRegistration($data));
                return response()->json(['alert' => 'success', 'message' => 'Sales Managers Created Successfully!']);
            }
            return response()->json(['alert' => 'error', 'message' => 'Sales Managers Not Created!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while Creating Sales Managers: ' . $e->getMessage()], 500);
        }
    }
    public function showSalesAgent($id)
    {
        $salesManager = SalesAgent::with('bankAccounts')->find($id);
        if (!$salesManager) {
            return response()->json(['alert' => 'error', 'message' => 'Sales Manager Not Found'], 500);
        }
        return response()->json($salesManager);
    }
    public function updateSalesAgent(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'phone' => 'required',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:1048',
            'account_number' => 'required|numeric|unique:user_accounts|min:16,' . $id,
            'account_name' => 'required|string|max:255',
            'account_holder_name' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $salesManager = SalesAgent::findOrFail($id);
            $salesManager->fill($request->only(['name', 'email', 'phone', 'user_type', 'status']));

            if ($request->hasFile('image')) {
                // Delete old image if exists
                $oldImagePath = public_path('admin/assets/images/users/' . $salesManager->image);

                // Delete old image if it exists
                if ($salesManager->image && file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('admin/assets/images/users'), $filename);
                $salesManager->image = 'public/admin/assets/images/users/' . $filename;
            }
            $salesManager->save();
            return response()->json(['alert' => 'success', 'message' => 'Sales Managers Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating Sub Admin: ' . $e->getMessage()], 500);
        }
    }
    public function deleteSalesAgent($id)
    {
        $salesManager = SalesAgent::findOrFail($id);
        $salesManager->delete();
        return response()->json(['alert' => 'success', 'message' => 'Sales Managers Deleted SuccessFully!']);
    }
    // ################ Block Sub Admin########
    public function updateAgentBlockStatus(Request $request, $id)
    {
        try {
            $user = SalesAgent::findOrFail($id);
            if ($user->status == '0') {
                $user->status = '1';
                // $data['username'] =  $user->name;
                // $data['useremail'] =  $user->email;
                // Mail::to($user->email)->send(new userUnBlocked($data));
                $message = 'Sales Manager Active Successfully';
            } else if ($user->status == '1') {
                $user->status = '0';
                // $data['username'] =  $user->name;
                // $data['useremail'] =  $user->email;
                // $data['reason'] = $request->reason;
                // Mail::to($user->email)->send(new userBlocked($data));
                $message = 'Sales Manager In Active Successfully';
            } else {
                return response()->json(['alert' => 'info', 'error' => 'User status is already updated or cannot be updated.']);
            }
            $user->save();
            return response()->json(['alert' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'error' => 'An error occurred while updating user status.']);
        }
    }
}
