<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QiPayService;
use Illuminate\Support\Str;
use App\Models\Order; 

class PaymentController extends Controller
{
    protected $qiPayService;

    public function __construct(QiPayService $qiPayService)
    {
        $this->qiPayService = $qiPayService;
    }

    public function makePayment(Request $request)
    {
        // Generate a UUID for the order ID
        $orderId = Str::uuid()->toString();
        $orderId = str_replace('-', '', $orderId);  // R
        // Get payment data from the request or set defaults
        $amount = $request->input('amount', 1000); // Default to 1000 IQD
        $currency = $request->input('currency', 'IQD');
        $successUrl =  url('success');
        $failureUrl =  url('failure');
        $cancelUrl =  url('cancel');
        $webhookUrl =  url('api/payment-webhook'); // Full URL to webhook
     

        // Use the QiPayService to make the payment
        $response = $this->qiPayService->makePayment(
            $orderId,
            $amount,
            $currency,
            $successUrl,
            $failureUrl,
            $cancelUrl,
            $webhookUrl
        );

        if($response['success']??'')
        {

 
        // Optionally, you can update the order status based on the payment response
        if ($response['success'] === true) {
              // Save the order to the database with status and state
              
            $order = Order::create([
                'order_id' => $orderId,
                'amount' => $amount,
                'currency' => $currency,
                'status' => 'pending', // Set the status to 'pending'
                'state' => 'initial',  // Set the state to 'initial'
                'link'=>$response['data']['link'] ,
                'token'=>$response['data']['token'],
                'transactionId'=>$response['data']['transactionId'],
                '3DSecureId'=>$response['data']['transactionId'],
            ]);
        } else {
            return response()->json($response);

         }
        }

        // Return the response as JSON
        return response()->json($response);
    }
}
