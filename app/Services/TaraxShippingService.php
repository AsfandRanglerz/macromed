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

    public function addPickupAddress(array $addressData)
    {
        $endpoint = "{$this->baseUrl}/api/pickup_address/add";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->post($endpoint, $addressData);

            if ($response->successful()) {
                return $response->json();
            }
            return [
                'error' => true,
                'message' => $response->json('message') ?? 'Failed to add pickup address.',
            ];

        } catch (Exception $e) {
            Log::error('Error in addPickupAddress: ' . $e->getMessage());
            return [
                'error' => true,
                'message' => 'An error occurred while adding the pickup address.',
            ];
        }
    }

    /**
     * Get List of Cities
     */
    public function getCities()
    {
        $endpoint = "{$this->baseUrl}/api/cities";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($endpoint);

            if ($response->successful()) {
                return $response->json();
            }

            return [
                'error' => true,
                'message' => $response->json('message') ?? 'Failed to fetch cities.',
            ];

        } catch (Exception $e) {
            Log::error('Error in getCities: ' . $e->getMessage());
            return [
                'error' => true,
                'message' => 'An error occurred while fetching the cities.',
            ];
        }
    }
}
