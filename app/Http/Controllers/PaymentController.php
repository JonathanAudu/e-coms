<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Unicodeveloper\Paystack\Facades\Paystack;
use App\Models\Order;

class PaymentController extends Controller
{
    public function redirectToPaystack($orderId)
    {
        $order = Order::findOrFail($orderId);

        return Paystack::getAuthorizationUrl([
            'amount' => $order->total * 100,
            'email' => $order->email,
            'reference' => $order->order_number,
            'callback_url' => route('paystack.callback'),
            'metadata' => [
                'order_id' => $order->id,
                'custom_fields' => [
                    ['display_name' => 'Customer Name', 'variable_name' => 'customer_name', 'value' => $order->first_name . ' ' . $order->last_name],
                ]
            ]
        ])->redirectNow();
    }

    public function handleGatewayCallback()
    {
        try {
            \Log::info('Paystack callback hit');
            $paymentDetails = Paystack::getPaymentData();
            \Log::info($paymentDetails);

            
            $reference = $paymentDetails['data']['reference'];
            $status = $paymentDetails['data']['status'];

            // Find the order by reference
            $order = Order::where('order_number', $reference)->first();

            if (!$order) {
                return redirect()->route('checkout.form')->with('error', 'Order not found.');
            }

            if ($status === 'success') {
                $order->update([
                    'payment_status' => 'completed',
                    'status' => 'processing',
                ]);
            } else {
                $order->update([
                    'payment_status' => 'failed',
                    'status' => 'cancelled',
                ]);
            }

            return redirect()->route('checkout.thankyou')->with('success', 'Payment processed successfully.');
        } catch (\Exception $e) {
            return redirect()->route('checkout.form')->with('error', 'Payment verification failed.');
        }
    }
}
