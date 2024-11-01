<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\PrivacyPolicy;
use App\Models\TermCondition;
use Illuminate\Http\Request;

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
}
