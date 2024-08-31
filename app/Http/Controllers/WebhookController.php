<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class WebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Retrieve the webhook payload
        $payload = $request->all();

        // Log the payload (optional)
        \Log::info('Webhook received: ', $payload);

        // Validate the payload structure
        if (!isset($payload['orderId'], $payload['transactionId'], $payload['status'])) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        // Extract the relevant information
        $orderId = $payload['orderId'];
        $transactionId = $payload['transactionId'];
        $status = $payload['status'];

        // Find the order by orderId in your database
        $order = Order::where('order_id', $orderId)->first();

        // Check if order exists
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Update the order status and state based on the webhook status
        $newState = 'processed';  // Example of updating the state when processing a webhook

        switch ($status) {
            case 'PENDING':
                $order->status = 'pending';
                break;
            case 'FAILURE':
                $order->status = 'failed';
                $newState = 'error';
                break;
            case 'CHECK_3D':
                $order->status = 'check_3d';
                $newState = 'awaiting_3d_check';
                break;
            case 'SUCCESS':
                $order->status = 'completed';
                $newState = 'success';
                break;
            case 'CANCELLED':
                $order->status = 'cancelled';
                $newState = 'cancelled';
                break;
            case 'VOID':
                $order->status = 'voided';
                $newState = 'void';
                break;
            case 'REVERSED':
                $order->status = 'reversed';
                $newState = 'reversed';
                break;
            case 'REFUNDED':
                $order->status = 'refunded';
                $newState = 'refunded';
                break;
            case 'SETTLED':
                $order->status = 'settled';
                $newState = 'settled';
                break;
            case 'AUTH_PAYER':
                $order->status = 'auth_payer';
                $newState = 'auth_payer';
                break;
            default:
                return response()->json(['error' => 'Invalid status'], 400);
        }

        // Save the updated order with status and state
        $order->update([
            'transaction_id' => $transactionId,  // Update the transaction ID
            'status' => $order->status,          // Update the status
            'state' => $newState,                // Update the state based on processing logic
        ]);
        
        \Log::info('Webhook processed successfully');

        // Respond with a success message
        return response()->json(['message' => 'Webhook processed successfully']);
    }
}
