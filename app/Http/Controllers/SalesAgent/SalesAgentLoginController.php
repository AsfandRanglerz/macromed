<?php

namespace App\Http\Controllers\SalesAgent;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SalesAgentLoginController extends Controller
{
    public function getAgentLoginPage()
    {
        return view('salesagent.auth.login');
    }

    public function loginSalesAgent(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');
        if (Auth::guard('sales_agent')->attempt($credentials)) {
            $salesAgent = Auth::guard('sales_agent')->user(); // Ensure you use the correct guard here
            if ($salesAgent->user_type === 'salesmanager' && $salesAgent->status == '1') {
                $request->session()->regenerate();
                return redirect('sales-agent/dashboard')->with(['alert' => 'success', 'message' => 'Login Successfully!']);
            } else {
                Auth::guard('sales_agent')->logout();
                return redirect('/sales-agent')->with(['alert' => 'error', 'error' => 'Only Sales Agent with active status can log in.']);
            }
        }
        return redirect('/sales-agent')->with(['alert' => 'error', 'error' => 'Invalid Email and Password!']);
    }
}
