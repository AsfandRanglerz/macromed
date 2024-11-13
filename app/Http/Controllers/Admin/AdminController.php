<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\Admin;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\PasswordResetMail;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\SalesAgent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    //
    public function getdashboard()
    {
        if (auth()->guard('admin')->check()) {
            // Fetch counts only if the admin is logged in
            $customerCount = User::where('user_type', 'customer')->count();
            $subAdminCount   = User::where('user_type', 'subadmin')->count();
            $salesAgentCount = SalesAgent::count();
            $productCount = Product::count();

            return view('admin.index', compact('customerCount', 'salesAgentCount', 'subAdminCount', 'productCount'));
        } elseif (auth()->guard('web')->check()) {
            return view('admin.index');
        }

        // If neither admin nor sub-admin is logged in, redirect to login
        return redirect()->route('login');
    }
    public function getProfile()
    {
        if (Auth::guard('web')->check()) {
            $data = User::find(Auth::guard('web')->id());
        } elseif (Auth::guard('admin')->check()) {
            $data = Admin::find(Auth::guard('admin')->id());
        } else {
            return redirect('/admin-login')->with(['alert' => 'error', 'error' => 'You Are Unable For Login!']);
        }
        return view('admin.auth.profile', compact('data'));
    }

    public function update_profile(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
        ]);
        $data = $request->only(['name', 'email', 'phone']);
        if (auth()->guard('web')->check()) {
            $user = User::find(auth()->guard('web')->id());
            if (!$user) {
                return back()->with(['alert' => 'error', 'message' => 'User not found.']);
            }
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move(public_path('admin/assets/images/users/'), $filename);
                $data['image'] = 'public/admin/assets/images/users/' . $filename;
            }

            $user->update($data);
        } elseif (auth()->guard('admin')->check()) {
            $admin = Admin::find(auth()->guard('admin')->id());
            if (!$admin) {
                return back()->with(['alert' => 'error', 'message' => 'Admin not found.']);
            }
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move(public_path('admin/assets/images/users/'), $filename);
                $data['image'] = 'public/admin/assets/images/users/' . $filename;
            }
            $admin->update($data);
        } else {
            return back()->with(['alert' => 'error', 'error' => 'Not authorized.']);
        }
        return back()->with(['alert' => 'success', 'message' => 'Profile Updated Successfully!']);
    }


    public function forgetPassword()
    {
        return view('admin.auth.forgetPassword');
    }
    public function adminResetPasswordLink(Request $request)
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
    public function change_password($id)
    {
        $user = DB::table('password_resets')->where('token', $id)->first();

        if (isset($user)) {
            return view('admin.auth.chnagePassword', compact('user'));
        }
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8',
            'confirmed' => 'required|same:password',
        ]);

        $password = bcrypt($request->password);

        $user = User::where('email', $request->email)->first();
        $admin = Admin::where('email', $request->email)->first();
        if ($user) {
            $user->update(['password' => $password]);
            DB::table('password_resets')->where('email', $request->email)->delete();
            return redirect('admin')->with(['alert' => 'success', 'message' => 'Password reset successfully']);
        } elseif ($admin) {
            $admin->update(['password' => $password]);
            DB::table('password_resets')->where('email', $request->email)->delete();
            return redirect('admin')->with(['alert' => 'success', 'message' => 'Password reset successfully']);
        }

        return back()->with(['alert' => 'error', 'error' => 'Invalid email or user not found']);
    }

    public function logout(Request $request)
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin-login')->with(['status' => true, 'message' => 'Log Out Successfully']);
    }
}
