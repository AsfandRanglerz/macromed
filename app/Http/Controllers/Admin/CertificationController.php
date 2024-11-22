<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\Certification;
use App\Http\Requests\CertificationRequest;


class CertificationController extends BaseController
{
    protected $model = Certification::class;
    protected $keys = ['name', 'status'];
    protected $formRequestClass = CertificationRequest::class;


    public function certificationData(Request $request)
    {
        try {
            $is_draft = $request->query('is_draft', '1');
            $certifications = $this->model::where('is_draft', $is_draft)->latest()->get();
            return response()->json(['data' => $certifications], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch category data',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function certificationIndex()
    {
        $certifications = Certification::all();
        return view('admin.certification.index', compact('certifications'));
    }

    public function showCertification($id)
    {
        $certification = $this->model::findOrFail($id);
        if (!$certification) {
            return response()->json(['alert' => 'error', 'message' => 'Certification Not Found'], 500);
        }
        return response()->json($certification);
    }
}
