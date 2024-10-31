<?php

namespace App\Http\Controllers\SalesAgent;

use Exception;
use App\Models\SalesAgent;
use App\Models\AgentWallet;
use Illuminate\Support\Str;
use App\Models\AgentAccount;
use Illuminate\Http\Request;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SalesAgentAuthController extends Controller
{
    public function getSalesAgentdashboard()
    {
        if (Auth::guard('sales_agent')->check()) {
            $data = Auth::guard('sales_agent')->user();
            $data['salesAgent']  = AgentWallet::where('sales_agent_id', auth()->guard('sales_agent')->id())->first();
        } else {
            return redirect('/sales-agent')->with(['alert' => 'error', 'message' => 'You are not logged in!']);
        }
        return view('salesagent.index', compact('data'));
    }
    public function getSalesAgentProfile()
    {
        if (Auth::guard('sales_agent')->check()) {
            $data = Auth::guard('sales_agent')->user();
            $data['salesAgent']  = AgentAccount::where('agent_id', auth()->guard('sales_agent')->id())->get();
        } else {
            return redirect('/sales-agent')->with(['alert' => 'error', 'message' => 'You are not logged in!']);
        }
        return view('salesagent.auth.profile', compact('data'));
    }

    public function sales_agent_update_profile(Request $request)
    {
        $salesAgentId = auth()->guard('sales_agent')->id();

        $request->validate([
            'name' => 'required|unique:sales_agents,name,' . $salesAgentId,
            'email' => 'required|email|unique:sales_agents,email,' . $salesAgentId,
            'phone' => 'required',
            'location' => 'required',
            'account_number' => 'required|numeric|unique:agent_accounts,account_number,' . $salesAgentId . ',agent_id|min:16',
            'account_name' => 'required|string|max:255',
            'account_holder_name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        if (auth()->guard('sales_agent')->check()) {
            $salesAgent = SalesAgent::find($salesAgentId);
            if (!$salesAgent) {
                return back()->with(['alert' => 'error', 'error' => 'Sales Agent not found.']);
            }

            // Update sales agent details
            $salesAgent->fill($request->only([
                'name',
                'email',
                'phone',
                'status',
                'country',
                'state',
                'city',
                'location'
            ]));

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if it exists
                $oldImagePath = $salesAgent->image;
                if ($oldImagePath && File::exists(public_path($oldImagePath))) {
                    File::delete(public_path($oldImagePath));
                }

                // Save new image
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('admin/assets/images/users'), $filename);
                $salesAgent->image = 'admin/assets/images/users/' . $filename;
            }

            $salesAgent->save();

            // Update account information for the agent
            $accountData = $request->only(['account_number', 'account_name', 'account_holder_name']);
            AgentAccount::updateOrCreate(
                ['agent_id' => $salesAgent->id],
                $accountData
            );

            return back()->with(['alert' => 'success', 'message' => 'Profile Updated Successfully!']);
        } else {
            return back()->with(['alert' => 'error', 'error' => 'Not authorized.']);
        }
    }




    public function salesAgentforgetPassword()
    {
        return view('salesagent.auth.forgetPassword');
    }
    public function salesAgentResetPasswordLink(Request $request)
    {
        // Validate the email
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:sales_agents,email|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $token = Str::random(30);
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
        ]);
        $data['url'] = url('salesAgent-change-password', $token);
        Mail::to($request->email)->send(new PasswordResetMail($data));
        if (Mail::failures()) {
            return back()->with(['alert' => 'error', 'error' => 'Reset Password Link Not Sent']);
        }
        return back()->with(['alert' => 'success', 'message' => 'Reset Password Link Sent Successfully']);
    }
    public function salesAgentChangePassword($id)
    {
        $user = DB::table('password_resets')->where('token', $id)->first();

        if (isset($user)) {
            return view('salesagent.auth.chnagePassword', compact('user'));
        }
    }
    public function salesAgentResetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8',
            'confirmed' => 'required|same:password',
        ]);

        $password = bcrypt($request->password);

        $salesAgent = SalesAgent::where('email', $request->email)->first();
        if ($salesAgent) {
            $salesAgent->update(['password' => $password]);
            DB::table('password_resets')->where('email', $request->email)->delete();
            return redirect('/sales-agent')->with(['alert' => 'success', 'message' => 'Password reset successfully']);
        } else {
            return back()->with(['alert' => 'error', 'error' => 'Invalid email or user not found']);
        }
    }

    public function salesAgentlogout(Request $request)
    {
        if (auth()->guard('sales_agent')->check()) {
            auth()->guard('sales_agent')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/sales-agent')->with(['alert' => 'success', 'message' => 'You Are Logout Successfully!']);
        } else {
            return back()->with(['alert' => 'error', 'error' => 'An error accour during logout!']);
        }
    }
}
