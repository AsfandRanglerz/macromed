<?php

namespace App\Services;

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
        $endpoint = "{$this->baseUrl}/pickup_address/add";

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
    }
    /**
     * Get List of Cities
     */
    public function getCities()
    {
        $endpoint = "{$this->baseUrl}/api/cities";

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
    }
}
