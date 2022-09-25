<?php

namespace App\Http\Controllers;

use App\Events\PaymentEvent;
use App\Events\TransactionEvents;
use App\Order;
use Illuminate\Http\Request;

class PaymentNotificationController extends Controller
{
    public function __invoke(Request $request)
    {
        $content = json_decode($request->getContent());

        $statusCode = $content->status_code;
        $transactionStatus = $content->transaction_status;
        $transactionId = $content->transaction_id;
        $transactionTime = $content->transaction_time;
        $paymentType = $content->payment_type;
        $orderId = $content->order_id;
        $va_numbers = $content->va_numbers[0] ?? null;
        $grossAmount = $content->gross_amount;
        $fraudStatus = $content->fraud_status;
        $billKey = $content->bill_key ?? null;
        $billerCode = $content->biller_code ?? null;

        if (substr($statusCode, 0, 1) == 2) {
            $order = Order::where('identifier', $orderId)->first();
            abort_if(empty($order), 404);
            $paymentEvent = new PaymentEvent(
                $order,
                $transactionId,
                $transactionTime,
                $paymentType,
                $va_numbers ? $va_numbers->va_number : null,
                $va_numbers ? $va_numbers->bank : null,
                $billerCode,
                $billKey
            );

            if ($transactionStatus == 'pending') {
                event(TransactionEvents::PENDING, $paymentEvent);
            } elseif ($transactionStatus == 'settlement') {
                event(TransactionEvents::SETTLEMENT, $paymentEvent);
            } elseif ($transactionStatus == 'expire') {
                event(TransactionEvents::EXPIRE, $paymentEvent);
            } else {
                //
            }
        }

        return response()->json(['message' => 'ok']);
    }
}
