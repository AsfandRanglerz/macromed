<?php

namespace App\Http\Controllers\Api;

use App\Models\FAQS;
use App\Models\About;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use App\Models\PrivacyPolicy;
use App\Models\TermCondition;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Mail\adminContactUs;
use App\Models\admin;
use App\Models\Blogs;
use App\Models\CareerSection;
use Illuminate\Support\Facades\Mail;

class SecurityController extends Controller
{
    public function getAboutUs()
    {
        $aboutUs = About::all();
        if ($aboutUs) {
            return response()->json([
                'status' => 'success',
                'aboutUs' => $aboutUs,
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Not Found!',
            ], 404);
        }
    }
    public function getPrivacyPolicy()
    {
        $privacyPolicy = PrivacyPolicy::all();
        if ($privacyPolicy) {
            return response()->json([
                'status' => 'success',
                'privacyPolicy' => $privacyPolicy,
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Not Found!',
            ], 404);
        }
    }
    public function getTermsCondation()
    {
        $termCondition = TermCondition::all();
        if ($termCondition) {
            return response()->json([
                'status' => 'success',
                'termCondition' => $termCondition,
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Not Found!',
            ], 404);
        }
    }
    public function faqs()
    {
        $faqs = FAQS::orderBy('position')->get();
        if ($faqs) {
            return response()->json([
                'status' => 'success',
                'faqs' => $faqs,
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Not Found!',
            ], 404);
        }
    }

    public function sendContactMessage(Request $request)
    {
        DB::beginTransaction();

        try {
            $admin = Admin::firstOrFail();  // Retrieve the admin's email
            $contact = ContactUs::create([
                'email' => $request->email,
                'message' => $request->message
            ]);

            Mail::to($admin->email)->send(new AdminContactUs($contact));
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Your message has been sent successfully!',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send message. Please try again later.' . $e->getMessage(),
            ], 500);
        }
    }
    public function blogs()
    {
        $blogs = Blogs::first();
        if ($blogs) {
            return response()->json([
                'status' => 'success',
                'blogs' => $blogs,
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Not Found!',
            ], 404);
        }
    }
    public function careerSection()
    {
        $careerSection = CareerSection::first();
        if ($careerSection) {
            return response()->json([
                'status' => 'success',
                'careerSections' => $careerSection,
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Not Found!',
            ], 404);
        }
    }
}
