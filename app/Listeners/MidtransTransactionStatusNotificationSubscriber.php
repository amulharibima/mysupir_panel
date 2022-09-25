<?php

namespace App\Listeners;

use App\Events\OrderEvent;
use App\Events\OrderEvents;
use App\Events\PaymentEvent;
use App\Events\TransactionEvents;
use App\Transaction;
use Illuminate\Events\Dispatcher;

class MidtransTransactionStatusNotificationSubscriber
{

    /**
     * Handle payment with pending status
     *
     * @param PaymentEvent $event
     *
     */
    public function handlePendingStatus(PaymentEvent $event)
    {
        $order = $event->order;

        $order->transaction->update([
            'midtrans_transaction_id' => $event->midtransTransactionId,
            'midtrans_transaction_time' => $event->midtransTransactionTime,
            'status' => Transaction::MENUNGGU_PEMBAYARAN,
            'payment_type' => $event->paymentType,
            'va_number' => $event->vaNumber,
            'bank' => $event->bank,
            'biller_code' => $event->billerCode,
            'bill_key' => $event->billKey
        ]);
    }

    /**
     * Handle payment with expire status
     *
     * @param PaymentEvent $event
     *
     */
    public function handleExpireStatus(PaymentEvent $event)
    {
        $order = $event->order;

        $order->transaction->update([
            'midtrans_transaction_id' => $event->midtransTransactionId,
            'midtrans_transaction_time' => $event->midtransTransactionTime,
            'status' => Transaction::KADALUARSA,
            'payment_type' => $event->paymentType,
            'va_number' => $event->vaNumber,
            'bank' => $event->bank,
            'biller_code' => $event->billerCode,
            'bill_key' => $event->billKey
        ]);

        if ($order->cancelOrder('Pembayaran kadaluarsa')) {
            event(OrderEvents::CANCELED, new OrderEvent($order));
        }
    }

    /**
     * Handle payment with settlement status
     *
     * @param PaymentEvent $event
     *
     */
    public function handleSettlementStatus(PaymentEvent $event)
    {
        $order = $event->order;

        $payment_settled = $order->transaction->update([
            'midtrans_transaction_id' => $event->midtransTransactionId,
            'midtrans_transaction_time' => $event->midtransTransactionTime,
            'status' => Transaction::SUDAH_DIBAYAR,
            'payment_type' => $event->paymentType,
            'va_number' => $event->vaNumber,
            'bank' => $event->bank,
            'biller_code' => $event->billerCode,
            'bill_key' => $event->billKey
        ]);

        if ($payment_settled) {
            event(OrderEvents::PAID, new OrderEvent($order));
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            TransactionEvents::PENDING,
            'App\Listeners\MidtransTransactionStatusNotificationSubscriber@handlePendingStatus'
        );

        $events->listen(
            TransactionEvents::SETTLEMENT,
            'App\Listeners\MidtransTransactionStatusNotificationSubscriber@handleSettlementStatus'
        );

        $events->listen(
            TransactionEvents::EXPIRE,
            'App\Listeners\MidtransTransactionStatusNotificationSubscriber@handleExpireStatus'
        );
    }
}
