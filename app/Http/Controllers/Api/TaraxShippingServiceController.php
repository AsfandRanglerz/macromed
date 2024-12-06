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
    // public function getCities()
    // {
    //     $response = $this->taraxApi->getCities();
    //     if (isset($response['error']) && $response['error']) {
    //         return response()->json($response, 400);
    //     }
    //     return response()->json($response);
    // }


    public function getCities()
    {
        $apiUrl =  'http://app.sonic.pk/api/cities'; // API URL
        $timeout = 30; // Timeout in seconds

        try {
            $client = new Client(['timeout' => $timeout]);

            // Retrieve API Token from environment variables
            $apiToken = 'djNWMjlpbkx2Yk1rT2R1WXN2YkJ5bWN5ZVpqbllxODhOQ0hEVlNzNVUwVVFpVmpNUzNZeHgyaUtjeDND67247f99afac4'; // Assuming the token is stored in your .env file

            // Make the GET request with the Authorization header
            $response = $client->get($apiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiToken,  // Add the API Token to the header
                ]
            ]);

            // Check if the response is successful
            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);  // Decode the JSON response
                return response()->json([
                    'error' => false,
                    'message' => 'Cities fetched successfully.',
                    'data' => $data,
                ]);
            }

            // If status code is not 200, return the appropriate message
            return response()->json([
                'error' => true,
                'message' => 'Unexpected response from the API.',
            ], $response->getStatusCode());
        } catch (RequestException $e) {
            // Handle specific HTTP errors
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : null;

            if ($statusCode === 500) {
                return response()->json([
                    'error' => true,
                    'message' => 'The API server encountered an error. Please try again later.',
                ]);
            }

            // Default error handling if request fails
            return response()->json([
                'error' => true,
                'message' => 'An error occurred while communicating with the API.',
                'exception' => $e->getMessage(),
            ], 500);
        }
    }
}
