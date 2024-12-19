<?php

namespace App\Http\Controllers\Api;

use Log;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\TaraxShippingService;
use GuzzleHttp\Exception\RequestException;

class TaraxShippingServiceController extends Controller
{
    protected $taraxApi;

    public function __construct(TaraxShippingService $taraxApi)
    {
        $this->taraxApi = $taraxApi;
    }

    public function addAddress(Request $request)
    {
        $data = $request->only(['person_of_contact', 'phone_number', 'email_address', 'address', 'city_id']);
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
