<?php

namespace App\Http\Controllers\Admin;

use App\Models\SilderImages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class SliderController extends Controller
{

    public function showSilderImage()
    {
        $silderImages = SilderImages::latest()->get();
        return view('admin.silder.index', compact('silderImages'));
    }
    public function uploadSilderImages(Request $request)
    {
        try {
            $request->validate([
                'image.*' => 'image|mimes:jpeg,png,jpg,gif|max:1024', // Maximum file size in kilobytes
            ], [
                'image.*.image' => 'The file must be an image.',
                'image.*.mimes' => 'Only JPEG, PNG, JPG, and GIF formats are allowed.',
                'image.*.max' => 'Maximum file size allowed is 1MB.',
            ]);
            if ($request->hasFile('image')) {
                foreach ($request->file('image') as $image) {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('admin/assets/images/silder'), $filename);
                    SilderImages::create([
                        'images' => 'public/admin/assets/images/silder/' . $filename,
                    ]);
                }
            }
            return redirect()->back()->with(['alert' => 'success', 'message' => 'Image Add Successfully!']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while uploading images: ' . $e->getMessage());
        }
    }
    public function updateSilderStatus(Request $request, $imageId)
    {
        try {
            $selectedImage = SilderImages::findOrFail($imageId);
            if ($selectedImage->status == '0') {
                $selectedImage->status = '1';
                $message = 'Product Image In Active Successfully';
            } else if ($selectedImage->status == '1') {
                $selectedImage->status = '0';
                $message = 'Product Image Active Successfully';
            } else {
                return response()->json(['alert' => 'info', 'error' => 'User status is already updated or cannot be updated.']);
            }
            $selectedImage->save();
            return response()->json(['alert' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update cover image']);
        }
    }

    public function deleteSilderImage($id)
    {
        try {
            $image = SilderImages::findOrFail($id);
            $imagePath =  $image->images;
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
            $image->delete();
            return redirect()->back()->with(['alert' => 'success', 'message' => 'Image Delete Successfully!']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete image: ' . $e->getMessage());
        }
    }
}