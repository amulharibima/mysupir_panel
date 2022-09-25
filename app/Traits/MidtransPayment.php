<?php
namespace App\Traits;

use App\Order;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Config;
use Midtrans;

trait MidtransPayment
{
    protected $isProduction;
    protected $merchantId;
    protected $clientKey;
    protected $serverKey;

    protected function setUp()
    {
        $this->isProduction = Config::get('services.midtrans.production');
        $this->merchantId = Config::get('services.midtrans.merchant_id');
        $this->clientKey = Config::get('services.midtrans.client_key');
        $this->serverKey = Config::get('services.midtrans.server_key');

        Midtrans\Config::$serverKey = $this->serverKey;
        Midtrans\Config::$isProduction = $this->isProduction;
        // FOR LOCAL DEVELOPMENT use https://webhook.site
        Midtrans\Config::$overrideNotifUrl = route('payment_notification_webhook');
        // Midtrans\Config::$overrideNotifUrl = 'https://webhook.site/935dcec2-804d-438f-9bae-5364744d9473';
    }

    public function createSnapToken(Order $order)
    {
        $this->setUp();

        try {
            $snap_token = Midtrans\Snap::createTransaction($this->setUpMidtransTransaction($order));
        } catch (Exception $e) {
            return $e;
        }

        return $snap_token;
    }

    public function setUpMidtransTransaction(Order $order)
    {
        return [
            'transaction_details' => $this->setTransactionDetails($order),
            'item_details' => $this->setItemDetails($order),
            'customer_details' => $this->setCustomerDetails($order),
            'expiry' => $this->setExpiryPayment(),
            'enabled_payments' => $this->setEnabledPaymentTypes()
        ];
    }

    public function setTransactionDetails(Order $order)
    {
        return [
            'order_id' => $order->getOrderIdentifier(),
            'gross_amount' => $order->transaction->total_price
        ];
    }

    public function setCustomerDetails(Order $order)
    {
        return [
            'first_name' => $order->user->name,
            'email' => $order->user->email,
            'phone' => '+62'.$order->user->phone_number,
        ];
    }

    public function setItemDetails(Order $order)
    {
        $items = [];
        foreach ($order->transaction->details as $i => $detail) {
            $items[$i] = [
                'name' => $detail->name,
                'price' => $detail->price,
                'quantity' => 1
            ];
        }

        return $items;
    }

    public function setExpiryPayment()
    {
        return [
            'unit' => 'minutes',
            'duration' => 15
        ];
    }

    public function setEnabledPaymentTypes()
    {
        return [
            'bank_transfer'
        ];
    }

    public function checkStatus($orderId)
    {
        $this->setUp();

        try {
            $transaction = Midtrans\Transaction::status($orderId);
        } catch (Exception $e) {
            return $e;
        }

        return $transaction;
    }

    public function getExpiredPaymentDate($transactionTime)
    {
        $expired_time_addition = $this->setExpiryPayment();

        $transactionTime = Carbon::createFromFormat('Y-m-d H:i:s', $transactionTime);

        return $transactionTime->add($expired_time_addition['unit'], $expired_time_addition['duration']);
    }
}
