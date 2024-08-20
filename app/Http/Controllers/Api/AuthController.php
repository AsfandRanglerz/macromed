<?php

namespace App\Http\Controllers\Api;

use App\Models\OTP;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\ResetPasswordMail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users')
                ],
            ]);
            if ($validation->fails()) {
                return response()->json(['errors' => $validation->errors()]);
            }
            $data = $request->only(['name', 'email', 'phone', 'profession', 'address']);
            $data['user_type'] = 'customer';
            $data['status'] = '1';
            $data['password'] = Hash::make($request->input('password'));
            $user = User::create($data);
            if (!$user) {
                return response()->json(['user' => []]);
            }
            return response()->json(['message' => 'User Registered Successfully!', 'user' => $user], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Registration failed. Please try again:' . $e->getMessage()]);
        }
    }

    public function login(Request $request)
    {
        try {
            // Find the user by email
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found',
                ], 404);
            }

            // Check if the user is blocked
            if ($user->status == 0) {
                return response()->json([
                    'status' => 'blocked',
                    'message' => 'You are blocked by the Admin',
                ], 403);
            }

            // Verify the password
            if (!Hash::check($request->password, $user->password)) {
                return response()->json(['error' => 'Password is not matched'], 401);
            }

            // Attempt to create a token with valid credentials
            if (!$token = JWTAuth::attempt($request->only(['email', 'password']))) {
                return response()->json(['error' => 'Invalid email or password'], 401);
            }
            if ($user) {
                $user->update(['is_active' => 1]);
                return response()->json([
                    'message' => 'Login successful',
                    'user' => $user,
                    'token' => $token,
                ], 200);
            }
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json(['error' => 'Login failed. Please try again. ' . $e->getMessage()], 500);
        }
    }
    public function userForgetPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
            ]);
            $exists = OTP::where('email', $request->email)->first();
            if ($exists) {
                return response()->json(['status' => 'error', 'message' => 'Reset Password OTP has already been sent']);
            }
            $otp = rand(1000, 9999);
            $token = Str::random(30);
            $user = User::where('email', $request->email)->first();
            $userId = $user->id;
            OTP::create([
                'email' => $request->email,
                'token' => $token,
                'otp' => $otp,
                'user_id' => $userId,
            ]);
            Mail::to($request->email)->send(new ResetPasswordMail($otp));
            return response()->json([
                'status' => 'success',
                'message' => 'OTP Sent Successfully',
                'data' => [
                    'email' => $request->email,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Invalid Email']);
        }
    }

    public function verifyOtp(Request $request)
    {
        try {
            $request->validate([
                'otp' => 'required',
            ]);
            $otpRecord = DB::table('otps')
                ->where('otp', $request->otp)
                ->first();
            if ($otpRecord) {
                return response()->json(['status' => 'success', 'message' => 'OTP is Verified'], 200);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Invalid OTP']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'status' => 'Failed',
                'errors' => $validator->errors(),
            ], 422);
        }
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'message' => 'User not found.',
                'status' => 'Failed',
            ], 404);
        }
        $otp = rand(1000, 9999);
        DB::table('otps')->where('user_id', $user->id)->update(['otp' => $otp]);
        Mail::to($request->email)->send(new ResetPasswordMail($otp));
        return response()->json([
            'message' => 'OTP resent successfully.',
            'status' => 'success',
        ], 200);
    }
    public function resetPassword(Request $request)
    {
        try {
            $password = Hash::make($request->password);
            $tags_data = [
                'password' => Hash::make($request->password)
            ];
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json(['error_message' => 'User not found']);
            }
            if ($user->update($tags_data)) {
                DB::table('otps')->where('email', $request->email)->delete();
                return response()->json(['status' => 'success', 'message' => 'Password reset successfully'], 200);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Failed to reset password']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }
    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            $user = Auth::user();
            if ($user) {
                $user->update(['is_active' => 0]);
            }
            return response()->json(['message' => 'Logout successful'], 200);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => 'The token is already invalidated or expired'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not log out the user: ' . $e->getMessage()], 500);
        }
    }
}
