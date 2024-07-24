<?php

namespace App\Http\Controllers\SalesAgent;

use App\Models\SalesAgent;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SalesAgentAuthController extends Controller
{
    public function getSalesAgentdashboard()
    {
        return view('salesagent.index');
    }
    public function getSalesAgentProfile()
    {
        if (Auth::guard('sales_agent')->check()) {
            $data = Auth::guard('sales_agent')->user();
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
                return back()->with(['alert' => 'error', 'error' => 'User not found.']);
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
            'email' => [
                'required',
                'email',
                'exists_in_users_or_admins' // Custom validation rule
            ],
        ], [
            'email.exists_in_users_or_admins' => 'This email does not exist.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // If validation passes, continue with sending the reset link
        $token = Str::random(30);
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
        ]);
        if ($token) {
            $data['url'] = url('change_password', $token);
            Mail::to($request->email)->send(new PasswordResetMail($data));
            return back()->with(['alert' => 'success', 'message' => 'Reset Password Link Sent Successfully']);
        } else {
            return back()->with(['alert' => 'error', 'message' => 'Reset Password Link Not Sent']);
        }
    }
    public function salesAgentChange_password($id)
    {
        $salesAgent = DB::table('password_resets')->where('token', $id)->first();

        if (isset($salesAgent)) {
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

    public function salesAgentlogout()
    {
        if (auth()->guard('sales_agent')->check()) {
            auth()->guard('sales_agent')->logout();
            return redirect('/sales-agent')->with(['alert' => 'success', 'message' => 'You Are Logout Successfully!']);
        } else {
            return back()->with(['alert' => 'error', 'error' => 'An error accour during logout!']);
        }
    }
}
