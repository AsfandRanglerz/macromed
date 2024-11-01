<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use App\Mail\userBlocked;
use App\Models\SalesAgent;
use App\Mail\userUnBlocked;
use App\Models\AgentWallet;
use App\Models\UserAccount;
use App\Models\AgentAccount;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Mail\SalesAgentRegistration;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SalesAgentController extends Controller
{
    public function salesagentData()
    {
        $salesManagers = SalesAgent::where('user_type', 'salesmanager')->with('agentAccounts')->latest()->get();
        $json_data["data"] = $salesManagers;
        return json_encode($json_data);
    }
    public function salesagentIndex()
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
        $salesManagers = SalesAgent::where('user_type', 'salesmanager')->with('agentAccounts')->latest()->get();
        return view('admin.salesagent.index', compact('salesManagers', 'countries'));
    }
    public function fetchStates(Request $request)
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

    public function fetchCities(Request $request)
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
    public function salesagentProfile($id)
    {
        $salesManager = SalesAgent::findOrFail($id);
        return view('admin.salesagent.profile', compact('salesManager'));
    }

    public function getPaymentHistory(Request $request, $id)
    {
        try {
            $user = SalesAgent::findOrFail($id);
            $paymentRequests = $user->withDrwalRequest()->where('status', 'approved')->latest()->get();
            $walletHistory=$user->agentWallet()->first();
            // return $walletHistory;
            return view('admin.salesagent.paymentHistory', compact('paymentRequests','walletHistory'));
        } catch (\Exception $e) {
            return response()->json([
                'alert' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function salesagentCreate(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('sales_agents')
                ],
                'email' => 'required|email|unique:sales_agents|max:255',
                'account_number' => 'required|numeric|unique:user_accounts|min:16',
                'password' => 'required|string|min:8|max:255',
                'phone' => 'required|numeric|unique:sales_agents|min:11',
                'country' => 'required',
                'state' => 'required',
                'city' => 'required',
                'location' => 'required',
                'confirmpassword' => 'required|same:password',
                'image' => 'nullable|image|mimes:jpeg,jpg,png|max:1048',
                'account_name' => 'required|string|max:255',
                'account_holder_name' => 'required|string|max:255'
            ]);

            if ($validator->fails()) {
                DB::rollBack();
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $data = $request->only(['name', 'email', 'phone', 'status', 'country', 'state', 'city', 'location']);
            $data['user_type'] = 'salesmanager';
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
                $account = new AgentAccount();
                $account->agent_id = $salesManager->id;
                $account->account_name = $request->account_name;
                $account->account_holder_name = $request->account_holder_name;
                $account->account_number = $request->account_number;
                $account->save();
                //######### Wallet  ###########
                $wallet = new AgentWallet();
                $wallet->sales_agent_id = $salesManager->id;
                $wallet->recevied_commission = 0;
                $wallet->pending_commission = 0;
                $wallet->total_commission = 0;
                $wallet->save();
                //######### Send Mail  ###########
                $data['subadminname'] = $salesManager->name;
                $data['subadminemail'] = $salesManager->email;
                $data['password'] = $request->password;
                $data['account_name'] = $salesManager->name;
                $data['account_holder_name'] = $salesManager->email;
                $data['account_number'] = $request->password;
                Mail::to($salesManager->email)->send(new SalesAgentRegistration($data));
                DB::commit();
                return response()->json(['alert' => 'success', 'message' => 'Sales Managers Created Successfully!']);
            } else {
                DB::rollBack();
                return response()->json(['alert' => 'error', 'message' => 'Sales Managers Not Created!']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while Creating Sales Managers: ' . $e->getMessage()], 500);
        }
    }
    public function showSalesAgent($id)
    {
        $salesManager = SalesAgent::with('agentAccounts')->find($id);
        if (!$salesManager) {
            return response()->json(['alert' => 'error', 'message' => 'Sales Manager Not Found'], 500);
        }
        return response()->json($salesManager);
    }
    public function updateSalesAgent(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sales_agents')->ignore($id)
            ],
            'email' => 'required|email|max:255|unique:sales_agents,email,' . $id,
            'phone' => 'required|numeric|min:11,' . $id,
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
            $salesManager->fill($request->only(['name', 'email', 'phone', 'status', 'country', 'state', 'city', 'location']));

            if ($request->hasFile('image')) {
                // Delete old image if exists
                $oldImagePath =  $salesManager->image;
                // Delete old image if it exists
                if ($salesManager->image &&  File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('admin/assets/images/users'), $filename);
                $salesManager->image = 'public/admin/assets/images/users/' . $filename;
            }
            $salesManager->save();
            // Update Account Info
            $accountData = $request->only(['account_number', 'account_name', 'account_holder_name']);
            AgentAccount::updateOrCreate(
                ['agent_id' => $id],
                $accountData
            );
            return response()->json(['alert' => 'success', 'message' => 'Sales Managers Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating Sub Admin: ' . $e->getMessage()], 500);
        }
    }
    public function deleteSalesAgent($id)
    {
        $salesManager = SalesAgent::findOrFail($id);
        $imagePath = $salesManager->image;
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
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
                $data['username'] =  $user->name;
                $data['useremail'] =  $user->email;
                Mail::to($user->email)->send(new userUnBlocked($data));
                $message = 'Sales Manager Active Successfully';
            } else if ($user->status == '1') {
                $user->status = '0';
                $data['username'] =  $user->name;
                $data['useremail'] =  $user->email;
                $data['reason'] = $request->reason;
                Mail::to($user->email)->send(new userBlocked($data));
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
