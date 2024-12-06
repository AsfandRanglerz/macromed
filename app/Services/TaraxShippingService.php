<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
     * Generic method to handle API requests.
     */
    private function makeRequest($method, $endpoint, $data = [])
    {
        try {
            $url = "{$this->baseUrl}/{$endpoint}";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->$method($url, $data);
            // return $response;
            if ($response->successful()) {
                return $response->json();
            }

            Log::error("API Error: {$response->body()}");
            return [
                'error' => true,
                'message' => $response->json('message') ?? 'API request failed.',
            ];
        } catch (Exception $e) {
            Log::error("Exception in API request: {$e->getMessage()}");
            return [
                'error' => true,
                'message' => 'An error occurred while communicating with the API.',
            ];
        }
    }

    /**
     * Add a pickup address.
     */
    public function addPickupAddress(array $addressData)
    {
        return $this->makeRequest('post', 'api/pickup_address/add', $addressData);
    }

    /**
     * Fetch cities.
     */
    public function getCities()
    {
        return $this->makeRequest('get', 'api/cities');
    }
}
