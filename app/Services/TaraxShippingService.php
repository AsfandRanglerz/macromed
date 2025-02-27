<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class TaraxShippingService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.tarax.base_url');
        $this->apiKey = config('services.tarax.api_key');
    }

    /**
     * Generic method to handle API requests using cURL.
     */
    public function makeRequest($method, $endpoint, $data = [])
    {
        try {
            $url = "{$this->baseUrl}/{$endpoint}";
            // return $url;
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey, 
                'Accept' => 'application/json',
            ])->$method($url, $data);

            if ($response->successful()) {
                return $response->json();
            }
            return [
                'error' => true,
                'message' => 'API request failed.',
                'details' => $response->status(),
            ];
        } catch (Exception $e) {
            return [
                'error' => true,
                'message' => 'An error occurred while communicating with the API.',
                'exception' => $e->getMessage(),
            ];
        }
    }

    /**
     * Add a pickup address.
     */
    public function addPickupAddress(array $addressData)
    {
        return $this->makeRequest('POST', 'api/pickup_address/add', $addressData);
    }

    /**
     * Fetch cities.
     */
    public function getCities()
    {
        return $this->makeRequest('GET', 'api/cities');
    }
}
