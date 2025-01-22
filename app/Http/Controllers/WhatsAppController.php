<?php

namespace App\Http\Controllers;

use App\Models\SystemConfig;
use App\Models\MessageLog;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendWhatsAppMessageJob; // Import the job
use Illuminate\Support\Facades\Cache;

class WhatsAppController extends Controller
{
    // Function to send WhatsApp message
    public function sendWhatsAppMessage($phoneNumber, $message)
    {

        try {
            // Fetch API key and base URL from system configuration
            $config = SystemConfig::first();
            $apiKey = $config->api_key;
            $baseUrl = env('WHATSAPP_API_URL', 'https://api.textmebot.com/send.php');
        
            // Acquire a lock to prevent race conditions
            $lock = Cache::lock('send-whatsapp-message-lock', 10); // Lock for 10 seconds
        
            if ($lock->get()) {
                try {
                    // Get the last sent message timestamp from cache or database
                    $lastMessageTime = Cache::get('last_message_time', null);
        
                    // Calculate delay
                    if ($lastMessageTime && now()->diffInSeconds($lastMessageTime) < 10) {
                        $delaySeconds = 10 - now()->diffInSeconds($lastMessageTime);
                    } else {
                        $delaySeconds = 0;
                    }
        
                    // Dispatch job with delay
                    dispatch(new SendWhatsAppMessageJob($phoneNumber, $message, $apiKey, $baseUrl))
                        ->delay(now()->addSeconds($delaySeconds));
        
                    // Update the cache with the new message timestamp
                    Cache::put('last_message_time', now()->addSeconds($delaySeconds), 60);
        
                    // Log the message in the database
                    MessageLog::create([
                        'phone_number' => $phoneNumber,
                        'sent_at' => now()->addSeconds($delaySeconds),
                    ]);
                } finally {
                    // Release the lock
                    $lock->release();
                }
            } else {
                // Log or handle the case where lock is not acquired
                Log::warning('Unable to acquire lock for sending WhatsApp message');
            }
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp message: ' . $e->getMessage(), [
                'phone_number' => $phoneNumber,
                'message' => $message,
            ]);
        }
        
    }
}

