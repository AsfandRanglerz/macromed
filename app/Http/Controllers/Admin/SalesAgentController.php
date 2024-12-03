<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use App\Mail\userBlocked;
use App\Models\SalesAgent;
use App\Mail\userUnBlocked;
use App\Models\AgentWallet;
use App\Models\UserAccount;
use Illuminate\Support\Str;
use App\Models\AgentAccount;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Mail\SalesAgentRegistration;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Traits\CountryApiRequestTrait;
use App\Http\Requests\SalesAgentRequest;
use Illuminate\Support\Facades\Validator;

class SalesAgentController extends Controller
{
    use CountryApiRequestTrait;
    public function salesagentData(Request $request)
    {
        try {
            $is_draft = $request->query('is_draft', '1');
            $salesManagers = SalesAgent::where('user_type', 'salesmanager')->with('agentAccounts')->where('is_draft', $is_draft)->latest()->get();
            return response()->json(['data' => $salesManagers], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch category data',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function salesagentIndex()
    {
        $countries = $this->fetchApiData('https://api.countrystatecity.in/v1/countries');
        if (isset($countries['error'])) {
            $countries = [];
        }
        $salesManagers = SalesAgent::where('user_type', 'salesmanager')->with('agentAccounts')->latest()->get();
        return view('admin.salesagent.index', compact('salesManagers', 'countries'));
    }
    public function fetchStates(Request $request)
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

    public function fetchCities(Request $request)
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
            $walletHistory = $user->agentWallet()->first();
            return view('admin.salesagent.paymentHistory', compact('paymentRequests', 'walletHistory'));
        } catch (\Exception $e) {
            return response()->json([
                'alert' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function salesagentAutoSave(Request $request)
    {
        try {
            $salesManager = $request->draft_id
                ? SalesAgent::find($request->draft_id)
                : new SalesAgent();
            $salesManager->fill($request->only(['name', 'email', 'phone', 'status', 'country', 'state', 'city', 'location']));
            $salesManager->user_type = 'salesmanager';
            $salesManager->is_draft = 0;
            $salesManager->status = '0';
            if ($request->hasFile('image')) {
                $oldImagePath =   $salesManager->image;
                if ($salesManager->image &&  File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('admin/assets/images/users'), $filename);
                $salesManager->image = 'public/admin/assets/images/users/' . $filename;
            }
            $salesManager->save();
            $accountData = $request->only(['account_number', 'account_name', 'account_holder_name']);
            AgentAccount::updateOrCreate(
                ['agent_id' => $salesManager->id],
                $accountData
            );
            return response()->json([
                'alert' => 'success',
                'message' => 'SubAdmin Created Successfully!',
                'draft_id' => $salesManager->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'alert' => 'error',
                'message' => 'An error occurred while creating SubAdmin: ' . $e->getMessage()
            ], 500);
        }
    }

    public function salesagentCreate(SalesAgentRequest $request)
    {
        try {
            DB::beginTransaction();

            $salesManager = $request->draft_id
                ? SalesAgent::find($request->draft_id)
                : new SalesAgent();
            $salesManager->fill($request->only(['name', 'email', 'phone', 'status', 'country', 'state', 'city', 'location']));
            $salesManager->user_type = 'salesmanager';
            $salesManager->is_draft = 1;
            $salesManager->status = '1';
            $generatedPassword = Str::random(8);
            $salesManager->password = bcrypt($generatedPassword);
            if ($request->hasFile('image')) {
                $oldImagePath = $salesManager->image;
                if ($salesManager->image && File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('admin/assets/images/users'), $filename);
                $salesManager->image = 'public/admin/assets/images/users/' . $filename;
            }

            $salesManager->save();

            // Update or Create Agent Account
            $accountData = $request->only(['account_number', 'account_name', 'account_holder_name']);
            AgentAccount::updateOrCreate(
                ['agent_id' => $salesManager->id],
                $accountData
            );
            AgentWallet::firstOrCreate(
                ['sales_agent_id' => $salesManager->id],
                [
                    'recevied_commission' => 0,
                    'pending_commission' => 0,
                    'total_commission' => 0,
                ]
            );
            if ($salesManager) {
                $data = [
                    'subadminname' => $salesManager->name,
                    'subadminemail' => $salesManager->email,
                    'password' => $generatedPassword,
                    'account_name' => $request->account_name,
                    'account_holder_name' => $request->account_holder_name,
                    'account_number' => $request->account_number,
                ];
                Mail::to($salesManager->email)->send(new SalesAgentRegistration($data));
                DB::commit();

                return response()->json(['alert' => 'success', 'message' => 'Sales Manager Created Successfully!']);
            } else {
                DB::rollBack();
                return response()->json(['alert' => 'error', 'message' => 'Sales Manager Not Created!']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while Creating Sales Manager: ' . $e->getMessage()], 500);
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
