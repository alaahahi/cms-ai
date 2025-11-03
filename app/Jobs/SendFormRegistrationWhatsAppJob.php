<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Message;

class SendFormRegistrationWhatsAppJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 120;
    public $tries = 3;

    protected $phoneNumber;
    protected $message;
    protected $apiKey;
    protected $baseUrl;
    protected $batchId;
    protected $totalMessages;
    protected $messageIndex;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($phoneNumber, $message, $apiKey, $baseUrl, $batchId, $totalMessages, $messageIndex)
    {
        $this->phoneNumber = $phoneNumber;
        $this->message = $message;
        $this->apiKey = $apiKey;
        $this->baseUrl = $baseUrl;
        $this->batchId = $batchId;
        $this->totalMessages = $totalMessages;
        $this->messageIndex = $messageIndex;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // Update progress in cache - mark as processing
            $this->updateProgress('processing', $this->messageIndex + 1);

            // Format phone number
            $formattedPhone = $this->phoneNumber;
            if (str_starts_with($formattedPhone, '0')) {
                $formattedPhone = substr($formattedPhone, 1);
            }
            $formattedPhone = '+964' . $formattedPhone;

            // Save message in the database with status 'pending'
            $messageRecord = Message::create([
                'phone_number' => $formattedPhone,
                'message' => $this->message,
                'status' => 'pending',
                'batch_id' => $this->batchId,
            ]);

            Log::info('Sending WhatsApp message', [
                'batch_id' => $this->batchId,
                'phone_number' => $formattedPhone,
                'message_index' => $this->messageIndex,
                'total' => $this->totalMessages,
            ]);

            // Send WhatsApp message via API
            $response = Http::get($this->baseUrl, [
                'recipient' => $formattedPhone,
                'apikey' => $this->apiKey,
                'text' => $this->message,
                'json' => 'yes',
            ]);
            
            if ($response->successful()) {
                $responseData = $response->json();
                
                // Update message status to 'sent'
                $messageRecord->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);

                // Update progress
                $this->updateProgress('sent', $this->messageIndex + 1);

                Log::info('WhatsApp message sent successfully', [
                    'batch_id' => $this->batchId,
                    'phone_number' => $formattedPhone,
                    'message_index' => $this->messageIndex,
                ]);

            } else {
                // Update message status to 'failed'
                $messageRecord->update([
                    'status' => 'failed',
                ]);

                // Update progress
                $this->updateProgress('failed', $this->messageIndex + 1);

                Log::error('Failed to send WhatsApp message', [
                    'batch_id' => $this->batchId,
                    'phone_number' => $formattedPhone,
                    'message_index' => $this->messageIndex,
                    'response' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            // Update progress on error
            $this->updateProgress('failed', $this->messageIndex + 1);

            Log::error('Error while sending WhatsApp message: ' . $e->getMessage(), [
                'batch_id' => $this->batchId,
                'phone_number' => $this->phoneNumber,
                'message_index' => $this->messageIndex,
                'error' => $e->getMessage()
            ]);

            // Re-throw to trigger retry mechanism
            throw $e;
        }
    }

    /**
     * Update progress in cache
     */
    protected function updateProgress($status, $completed)
    {
        $progressKey = "whatsapp_batch_progress_{$this->batchId}";
        
        $progress = Cache::get($progressKey, [
            'batch_id' => $this->batchId,
            'total' => $this->totalMessages,
            'completed' => 0,
            'sent' => 0,
            'failed' => 0,
            'status' => 'processing',
            'last_update' => now()->toDateTimeString(),
        ]);

        $progress['completed'] = $completed;
        $progress['last_update'] = now()->toDateTimeString();
        
        if ($status === 'sent') {
            $progress['sent']++;
        } elseif ($status === 'failed') {
            $progress['failed']++;
        }

        // Check if all messages are completed
        if ($completed >= $this->totalMessages) {
            $progress['status'] = 'completed';
        } else {
            $progress['status'] = 'processing';
        }

        // Store in cache for 1 hour
        Cache::put($progressKey, $progress, 3600);
    }
}

