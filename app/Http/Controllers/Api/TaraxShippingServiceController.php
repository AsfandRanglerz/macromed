<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TaraxShippingService;
use Illuminate\Http\Request;

class TaraxShippingServiceController extends Controller
{
    protected $taraxApi;

    public function __construct(TaraxShippingService $taraxApi)
    {
        $this->taraxApi = $taraxApi;
    }

    public function addPickupAddress(Request $request)
    {
        $request->validate([
            'person_of_contact' => 'required|string',
            'phone_number'    => 'required|integer',
            'Email_address'     => 'required|string',
            'address' => 'required|string',
            'city_id' => 'required|integer'
        ]);
        $data = $request->only(['person_of_contact', 'phone_number', 'Email_address', 'address', 'city_id']);
        $response = $this->taraxApi->addPickupAddress($data);
        if (isset($response['error']) && $response['error']) {
            return response()->json($response, 400);
        }

        return response()->json($response, 200);
    }

    /**
     * Get List of Cities
     */
    public function getCities()
    {
        $response = $this->taraxApi->getCities();
        if (isset($response['error']) && $response['error']) {
            return response()->json($response, 400);
        }
        return response()->json($response);
    }
}
