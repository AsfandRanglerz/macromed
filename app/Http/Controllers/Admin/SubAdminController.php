<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use App\Mail\userBlocked;
use App\Mail\userUnBlocked;
use ModelNotFoundException;
use Illuminate\Http\Request;
use App\Mail\subAdminRegistration;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class SubAdminController extends Controller
{
    public function subadminData()
    {
        $subAdmins = User::where('user_type', 'subadmin')->latest()->get();
        $json_data["data"] = $subAdmins;
        return json_encode($json_data);
    }
    public function subadminIndex()
    {
        $subAdmins = User::where('user_type', 'subadmin')->latest()->get();
        $permissions = Permission::all();
        return view('admin.subadmin.index', compact('subAdmins', 'permissions'));
    }
    public function subAdminProfile($id)
    {
        $subAdmin = User::findOrFail($id);
        return view('admin.subadmin.subadminprofile', compact('subAdmin'));
    }
    public function subadminCreate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users|max:255',
                'password' => 'required|string|min:8|max:255',
                'phone' => 'required|unique:users|min:11',
                'confirmpassword' => 'required|same:password',
                'user_type' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,jpg,png|max:1048'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $data = $request->only(['name', 'email', 'phone', 'user_type', 'status']);
            $data['password'] = bcrypt($request->input('password'));
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('admin/assets/images/users'), $filename);
                $data['image'] = 'public/admin/assets/images/users/' . $filename;
            }
            $subadmin = User::create($data);
            if ($subadmin) {
                $data['subadminname'] = $subadmin->name;
                $data['subadminemail'] = $subadmin->email;
                $data['password'] = $request->password;
                Mail::to($subadmin->email)->send(new subAdminRegistration($data));
                return response()->json(['alert' => 'success', 'message' => 'SubAdmin Created Successfully!']);
            }
            return response()->json(['alert' => 'error', 'message' => 'SubAdmin Not Created!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while Creating SubAdmin: ' . $e->getMessage()], 500);
        }
    }
    public function showSubAdmin($id)
    {
        $subadmin = User::find($id);
        if (!$subadmin) {
            return response()->json(['alert' => 'error', 'message' => 'Sub Admin Not Found'], 500);
        }
        return response()->json($subadmin);
    }
    public function updateAdmin(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'phone' => 'required',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:1048'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $subadmin = User::findOrFail($id);
            $subadmin->fill($request->only(['name', 'email', 'phone', 'user_type', 'status']));

            if ($request->hasFile('image')) {
                // Delete old image if exists
                $oldImagePath = public_path('admin/assets/images/users/' . $subadmin->image);

                // Delete old image if it exists
                if ($subadmin->image && file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('admin/assets/images/users'), $filename);
                $subadmin->image = 'public/admin/assets/images/users/' . $filename;
            }
            $subadmin->save();
            return response()->json(['alert' => 'success', 'message' => 'SubAdmin Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating Sub Admin: ' . $e->getMessage()], 500);
        }
    }


    public function deleteSubadmin($id)
    {
        $subadmin = User::findOrFail($id);
        $subadmin->delete();
        return response()->json(['alert' => 'success', 'message' => 'SubAdmin Deleted SuccessFully!']);
    }
    // ######### Permissions Code ###########
    public function fetchUserPermissions(User $user)
    {
        $permissions = $user->permissions()->get();
        return response()->json(['permissions' => $permissions]);
    }
    public function updatePermissions(Request $request, User $user)
    {
        try {
            $permissions = $request->input('permissions', []);
            $permissions = array_map('intval', $permissions);
            $user->syncPermissions($permissions);
            return response()->json(['alert' => 'success', 'message' => 'Permissions updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating permissions' . $e->getMessage()], 500);
        }
    }
    // ################ Block Sub Admin########
    public function updateBlockStatus($id)
    {
        try {
            $user = User::findOrFail($id);
            if ($user->status == '0') {
                $user->status = '1';
                // $data['username'] =  $user->name;
                // $data['useremail'] =  $user->email;
                // Mail::to($user->email)->send(new userBlocked($data));
                $message = 'Sub Admin Active Successfully';
            } else if ($user->status == '1') {
                $user->status = '0';
                // $data['username'] =  $user->name;
                // $data['useremail'] =  $user->email;
                // Mail::to($user->email)->send(new userUnBlocked($data));
                $message = 'Sub Admin In Active Successfully';
            } else {
                return response()->json(['alert' => 'info', 'message' => 'User status is already updated or cannot be updated.']);
            }
            $user->save();
            return response()->json(['alert' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating user status.']);
        }
    }
}
