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
use App\Mail\UserResetPasswordMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
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
                return response()->json(['errors' => $validation->errors()], 400);
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
                    'error' => 'User not found',
                ], 404);
            }

            // Check if the user is blocked
            if ($user->status == 0) {
                return response()->json([
                    'error' => 'You are blocked by the Admin',
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
                    'message' => 'Login Successfully',
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
                'email' => 'required|email',
            ]);

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json([
                    'error' => 'Email address not found',
                ], 404);
            }

            // Check if an OTP already exists for this email and delete it
            $existingOtp = OTP::where('email', $request->email)->first();
            if ($existingOtp) {
                $existingOtp->delete();
            }
            $otp = rand(1000, 9999);
            $token = Str::random(30);
            OTP::create([
                'email' => $request->email,
                'token' => $token,
                'otp' => $otp,
                'user_id' => $user->id,
            ]);
            Mail::to($request->email)->send(new UserResetPasswordMail($otp));
            return response()->json([
                'message' => 'OTP Sent Successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while processing your request: ' . $e->getMessage(),
            ], 500);
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
                return response()->json(['message' => 'OTP is Verified'], 200);
            } else {
                return response()->json(['error' => 'Invalid OTP'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    // public function resendOtp(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'error' => $validator->errors(),
    //         ], 422);
    //     }
    //     $user = User::where('email', $request->email)->first();
    //     if (!$user) {
    //         return response()->json([
    //             'error' => 'User not found.',
    //         ], 404);
    //     }
    //     $otp = rand(1000, 9999);
    //     DB::table('otps')->where('user_id', $user->id)->update(['otp' => $otp]);
    //     Mail::to($request->email)->send(new ResetPasswordMail($otp));
    //     return response()->json([
    //         'success' => 'OTP resend successfully.',
    //     ], 200);
    // }
    public function resetPassword(Request $request)
    {
        try {
            $password = Hash::make($request->password);
            $tags_data = [
                'password' => Hash::make($request->password)
            ];
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json(['error' => 'User not found']);
            }
            if ($user->update($tags_data)) {
                DB::table('otps')->where('email', $request->email)->delete();
                return response()->json(['success' => 'Password reset successfully'], 200);
            } else {
                return response()->json(['error' => 'Failed to reset password']);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong: ' . $e->getMessage()], 500);
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
            return response()->json(['success' => 'Logout successful'], 200);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => 'The token is already invalidated or expired'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not log out the user: ' . $e->getMessage()], 500);
        }
    }

    public function userProfile(Request $request, $id)
    {
        try {
            $userProfile = User::where('id', $id)
                ->where('user_type', 'customer')
                ->select('id', 'name', 'email', 'phone', 'profession', 'location', 'image')
                ->first();
            if (!$userProfile) {
                return response()->json(['error' => 'User not found!'], 404);
            }
            if ($request->isMethod('get')) {
                return response()->json(['user' => $userProfile], 200);
            } elseif ($request->isMethod('put')) {
                $validatedData = $request->validate([
                    'email'      => 'sometimes|email|max:255|unique:users,email,' . $id,
                ]);
                $userProfile->update($validatedData);
                if ($request->hasFile('image')) {
                    // Delete old image if it exists
                    $oldImagePath =  $userProfile->image;
                    if ($userProfile->image && File::exists(public_path($oldImagePath))) {
                        File::delete(public_path($oldImagePath));
                    }
                    $image = $request->file('image');
                    $filename = time() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('admin/assets/images/users'), $filename);
                    $userProfile->image = 'admin/assets/images/users/' . $filename;
                    $userProfile->save();
                }
                return response()->json(['success' => 'User updated successfully', 'data' => $userProfile], 200);
            } else {
                // If the request method is not GET or PUT/PATCH, return a method not allowed response
                return response()->json(['error' => 'Method not allowed'], 405);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function updatePassword(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            if (!Hash::check($request->old_password, $user->password)) {
                return response()->json(['error' => 'Old password is incorrect'], 400);
            }
            if (Hash::check($request->password, $user->password)) {
                return response()->json(['error' => 'New password cannot be the same as the old password'], 400);
            }
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json(['success' => 'Password updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
}
