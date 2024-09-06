<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QiPayService;
use Illuminate\Support\Str;
use App\Models\Order; 
use Illuminate\Support\Facades\Http;


class PaymentController extends Controller
{
    protected $qiPayService;

    public function __construct(QiPayService $qiPayService)
    {
        $this->qiPayService = $qiPayService;
    }

    public function makePayment(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'familyNames' => 'nullable|string',
            'cardNumber' => 'nullable|string',
            'address' => 'nullable|string',
            'salse' => 'nullable|string',
        ]);

        // Generate a UUID for the order ID
        $orderId = Str::uuid()->toString();
        $orderId = str_replace('-', '', $orderId);  // R
        // Get payment data from the request or set defaults
        $amount = $request->input('amount', 85000); // Default to 1000 IQD
        $currency = $request->input('currency', 'IQD');
        $name = $request->input('name', '');
        $phone = $request->input('phone', '');
        $familyNames = $request->input('familyNames', '');
        $cardNumber = $request->input('cardNumber', '');
        $address = $request->input('address', '');
        $salse = $request->input('salse', 'بدون مندوب');

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
                'name' => $name,
                'phone' => $phone,
                'familyNames' => $familyNames,
                'card_number' => $cardNumber,
                'address' => $address,
                'salse' => $salse,
                'status' => 'pending', // Set the status to 'pending'
                'state' => 'initial',  // Set the state to 'initial'
                'link'=>$response['data']['link'] ,
                'token'=>$response['data']['token'],
                'transactionId'=>$response['data']['transactionId'],
                '3DSecureId'=>$response['data']['transactionId'],
            ]);

            return redirect($response['data']['link']);

        } else {
            return redirect()->back()->with('error', 'فشلت عملية الدفع. يرجى المحاولة مرة أخرى.');

         }
        }

        // Return the response as JSON
        return response()->json($response);
    }
}
