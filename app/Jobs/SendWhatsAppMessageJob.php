<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\Message; // Import the Message model

class SendWhatsAppMessageJob implements ShouldQueue
{
    use Queueable, Dispatchable;

    protected $phoneNumber;
    protected $message;
    protected $apiKey;
    protected $baseUrl;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($phoneNumber, $message, $apiKey, $baseUrl)
    {
        $this->phoneNumber = $phoneNumber;
        $this->message = $message;
        $this->apiKey = $apiKey;
        $this->baseUrl = $baseUrl;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // Save message in the database with status 'pending'
            $messageRecord = Message::create([
                'phone_number' => $this->phoneNumber,
                'message' => $this->message,
                'status' => 'pending'
            ]);
            Log::info('Sending WhatsApp message at ' . now()->toDateTimeString(), [
                'phone_number' => $this->phoneNumber,
                'message' => $this->message
            ]);


            // Sending WhatsApp message via API
            // $response = Http::get($this->baseUrl, [
            //     'recipient' => $this->phoneNumber,
            //     'apikey' => $this->apiKey,
            //     'text' => $this->message,
            //     'json' => 'yes',
            // ]);
            
            if ($response->successful()) {
                // Update message status to 'sent' and set sent time
                $messageRecord->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
              
            } else {
                // Update message status to 'failed'
                $messageRecord->update([
                    'status' => 'failed',
                ]);
                Log::error('Failed to send WhatsApp message. API responded with error.', [
                    'phone_number' => $this->phoneNumber,
                    'message' => $this->message,
                    'response' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            // Log error if there is an exception
            Log::error('Error while sending WhatsApp message: ' . $e->getMessage(), [
                'phone_number' => $this->phoneNumber,
                'message' => $this->message
            ]);
        }
    }
}
