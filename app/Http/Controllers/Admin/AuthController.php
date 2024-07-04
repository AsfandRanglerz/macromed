<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function getLoginPage()
    {
        return view('admin.auth.login');
    }
    // public function Login(Request $request)
    // {

    //     $request->validate([
    //         'email' => 'required',
    //         'password' => 'required',
    //     ]);
    //     $remember_me = ($request->remember_me) ? true : false;
    //     if (!auth()->guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $remember_me)) {
    //         return back()->with('err_message', 'Invalid email or password');
    //     }

    //     return redirect('admin/dashboard')->with('message','Login Successfully!');
    // }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');
        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect('admin/dashboard')->with(['alert' => 'success', 'message' => 'Login Successfully!']);
        }
        if (Auth::guard('web')->attempt($credentials)) {
            $user = Auth::user();
            if ($user->user_type === 'subadmin' && $user->status == '1') {
                return redirect('admin/dashboard')->with(['alert' => 'success', 'message' => 'Login Successfully!']);
            } else {
                Auth::logout();
                return redirect('/admin')->with(['alert' => 'error', 'error' => 'Only Subadmins with active status can log in.']);
            }
        }
        return redirect('/admin')->with(['alert' => 'error', 'error' => 'Invalid Email and Password!']);
    }
}
