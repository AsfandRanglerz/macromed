<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\TaraxShippingService;

class TaraxShippingServiceController extends Controller
{
    protected $taraxApi;

    public function __construct(TaraxShippingService $taraxApi)
    {
        $this->taraxApi = $taraxApi;
    }

    /**
     * Add a pickup address.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addPickupAddress(Request $request)
    {
        $request->validate([
            'person_of_contact' => 'required|string',
            'phone_number' => 'required|integer',
            'email_address' => 'required|email',
            'address' => 'required|string',
            'city_id' => 'required|integer',
        ]);

        $data = $request->only([
            'person_of_contact',
            'phone_number',
            'email_address',
            'address',
            'city_id',
        ]);

        $response = $this->taraxApi->addPickupAddress($data);

        if (isset($response['error']) && $response['error']) {
            return response()->json($response, 400);
        }

        return response()->json($response, 200);
    }

    /**
     * Get the list of cities.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCities()
    {
        $response = $this->taraxApi->getCities();

        if (isset($response['error']) && $response['error']) {
            return response()->json($response, 400);
        }

        return response()->json($response, 200);
    }
}
