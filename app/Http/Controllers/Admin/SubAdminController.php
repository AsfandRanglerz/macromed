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
use App\Http\Requests\SubAdminRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class SubAdminController extends Controller
{
    public function subadminData(Request $request)
    {
        try {
            $is_draft = $request->query('is_draft', '1');
            $subAdmins = User::where('user_type', 'subadmin')->where('is_draft', $is_draft)->latest()->get();
            return response()->json(['data' => $subAdmins], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch category data',
                'message' => $e->getMessage(),
            ], 500);
        }
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
    public function subadminAutoSave(Request $request)
    {
        try {
            $subAdmin = $request->draft_id
                ? User::find($request->draft_id)
                : new User();
            $subAdmin->fill($request->only(['name', 'email', 'phone']));
            $subAdmin->user_type = 'subadmin';
            $subAdmin->is_draft = 0;
            $subAdmin->status = '0';
            $subAdmin->password = bcrypt($request->input('password'));
            if ($request->hasFile('image')) {
                $oldImagePath =   $subAdmin->image;
                if ($subAdmin->image &&  File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('admin/assets/images/users'), $filename);
                $subAdmin->image = 'public/admin/assets/images/users/' . $filename;
            }
            $subAdmin->save();
            return response()->json([
                'alert' => 'success',
                'message' => 'SubAdmin Created Successfully!',
                'draft_id' => $subAdmin->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'alert' => 'error',
                'message' => 'An error occurred while creating SubAdmin: ' . $e->getMessage()
            ], 500);
        }
    }
    public function subadminCreate(SubAdminRequest $request)
    {
        try {
            $subAdmin = $request->draft_id
                ? User::findOrFail($request->draft_id)
                : new User();
            $subAdmin->fill($request->only(['name', 'email', 'phone']));
            $subAdmin->user_type = 'subadmin';
            $subAdmin->is_draft = 1;
            $subAdmin->status = '1';
            $subAdmin->password = bcrypt($request->input('password'));
            if ($request->hasFile('image')) {
                $oldImagePath =   $subAdmin->image;
                if ($subAdmin->image &&  File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('admin/assets/images/users'), $filename);
                $subAdmin->image = 'public/admin/assets/images/users/' . $filename;
            }
            $subAdmin->save();
            if ($subAdmin->is_draft == 1 && $subAdmin->status == '1') {
                $emailData = [
                    'subadminname' => $subAdmin->name,
                    'subadminemail' => $subAdmin->email,
                    'password' => $request->password,
                ];

                // Mail::to($subAdmin->email)->send(new subAdminRegistration($emailData));
                return response()->json(['alert' => 'success', 'message' => 'SubAdmin Created Successfully!']);
            }
            return response()->json(['alert' => 'error', 'message' => 'SubAdmin Not Created!']);
        } catch (\Exception $e) {
            return response()->json([
                'alert' => 'error',
                'message' => 'An error occurred while creating SubAdmin: ' . $e->getMessage()
            ], 500);
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

    public function deleteSubadmin($id)
    {
        $subadmin = User::findOrFail($id);
        $imagePath = $subadmin->image;
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
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
    public function updateBlockStatus(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            if ($user->status == '0') {
                $user->status = '1';
                $data['username'] =  $user->name;
                $data['useremail'] =  $user->email;
                Mail::to($user->email)->send(new userUnBlocked($data));
                $message = 'Sub Admin Active Successfully';
            } else if ($user->status == '1') {
                $user->status = '0';
                $data['username'] =  $user->name;
                $data['useremail'] =  $user->email;
                $data['reason'] = $request->reason;
                Mail::to($user->email)->send(new userBlocked($data));
                $message = 'Sub Admin In Active Successfully';
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
