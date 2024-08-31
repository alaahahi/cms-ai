<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class QiPayService
{
    protected $client;
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        // Initialize the Guzzle HTTP client
        $this->client = new Client();

        // Retrieve the API key and base URL from the .env file
        $this->apiKey = env('QI_PAY_API_KEY');
        $this->baseUrl = env('QI_PAY_BASE_URL');
    }

    public function makePayment($orderId, $amount, $currency, $successUrl, $failureUrl, $cancelUrl, $webhookUrl)
    {
        // Set up the headers
        $headers = [
            'Authorization' => $this->apiKey, // Use the API key from the env file
            'Content-Type' => 'application/json',
        ];

        // Prepare the body of the request
        $body = json_encode([
            'order' => [
                'amount' => $amount,
                'currency' => $currency,
                'orderId' => $orderId,
            ],
            'timestamp' => now()->toIso8601String(), // Current timestamp in ISO 8601 format
            'successUrl' => $successUrl,
            'failureUrl' => $failureUrl,
            'cancelUrl' => $cancelUrl,
            'webhookUrl' => $webhookUrl,
        ]);

        try {
            // Create the request object
            $request = new \GuzzleHttp\Psr7\Request('POST', $this->baseUrl . '/api/v0/transactions/business/token', $headers, $body);

            // Send the request asynchronously
            $response = $this->client->sendAsync($request)->wait();

            // Return the response body
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            // Handle any errors that occur during the request
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'status_code' => $e->getCode(),
            ];
        }
    }
}
