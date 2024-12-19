<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class TaraxShippingService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('services.tarax.base_url'),
            'headers' => [
                'Authorization' => config('services.tarax.api_key'), // Fixed API key access
                'Content-Type' => 'application/json',
            ],

            'timeout' => 10.0, // Timeout in seconds
        ]);
    }

    /**
     * Generic method to handle API requests using Guzzle.
     *
     * @param string $method
     * @param string $endpoint
     * @param array $options
     * @return array
     */
    public function makeRequest(string $method, string $endpoint, array $options = []): array
    {
        try {
            $response = $this->client->request(strtoupper($method), $endpoint, $options);
return  $response;
            return [
                'error' => false,
                'data' => json_decode($response->getBody(), true),
            ];
        } catch (RequestException $e) {
            Log::error("Guzzle Request Exception: {$e->getMessage()}");

            $responseBody = $e->hasResponse()
                ? json_decode($e->getResponse()->getBody(), true)
                : ['message' => 'Unable to process the request'];

            return [
                'error' => true,
                'message' => $responseBody['message'] ?? 'An error occurred',
                'details' => $e->getCode(),
            ];
        } catch (\Exception $e) {
            Log::error("Exception: {$e->getMessage()}");

            return [
                'error' => true,
                'message' => 'An unexpected error occurred',
                'details' => $e->getCode(),
            ];
        }
    }

    /**
     * Add a pickup address.
     *
     * @param array $addressData
     * @return array
     */
    public function addPickupAddress(array $addressData): array
    {
        return $this->makeRequest('POST', 'api/pickup_address/add', [
            'json' => $addressData, // Automatically converts to JSON
        ]);
    }

    /**
     * Fetch cities.
     *
     * @return array
     */
    public function getCities(): array
    {
        return $this->makeRequest('GET', 'api/cities');
    }
}
