<?php

namespace App\Http\Controllers;

use App\Models\SystemConfig;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendWhatsAppMessageJob; // Import the job

class WhatsAppController extends Controller
{
    // Function to send WhatsApp message
    public function sendWhatsAppMessage($phoneNumber, $message)
    {
        try {
            // Fetch API key and base URL from system configuration
            $config = SystemConfig::first(); // Get the API key from system configuration
            $apiKey = $config->api_key;
            $baseUrl = env('WHATSAPP_API_URL', 'https://api.textmebot.com/send.php'); // Fetch from .env or fallback to a default value
            // Send the message asynchronously via a job
            dispatch(new SendWhatsAppMessageJob($phoneNumber, $message, $apiKey, $baseUrl))->delay(now()->addSeconds(5)); 
        } catch (\Exception $e) {
            // Log detailed error for easier debugging
            Log::error('Failed to send WhatsApp message: ' . $e->getMessage(), [
                'phone_number' => $phoneNumber,
                'message' => $message
            ]);
        }
    }
}
