<?php

namespace App\Http\Controllers\SalesAgent;

use App\Models\SalesAgent;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\AgentWallet;
use Illuminate\Support\Facades\Auth;
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
            $data['salesAgent']  = AgentWallet::where('sales_agent_id', auth()->guard('sales_agent')->id())->get();
        } else {
            return redirect('/sales-agent')->with(['alert' => 'error', 'message' => 'You are not logged in!']);
        }
        return view('salesagent.auth.profile', compact('data'));
    }
    public function sales_agent_update_profile(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
        ]);
        $data = $request->only(['name', 'email', 'phone']);
        if (auth()->guard('sales_agent')->check()) {
            $salesAgent = SalesAgent::find(auth()->guard('sales_agent')->id());
            if (!$salesAgent) {
                return back()->with(['alert' => 'error', 'error' => 'Sales Agent not found.']);
            }
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move(public_path('admin/assets/images/users/'), $filename);
                $data['image'] = 'public/admin/assets/images/users/' . $filename;
            }

            $salesAgent->update($data);
        } else {
            return back()->with(['alert' => 'error', 'error' => 'Not authorized.']);
        }
        return back()->with(['alert' => 'success', 'message' => 'Profile Updated Successfully!']);
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
