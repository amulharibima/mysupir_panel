<?php

namespace App\Events;

use App\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $midtransTransactionId;
    public $midtransTransactionTime;
    public $paymentType;
    public $vaNumber;
    public $bank;
    public $grossAmount;
    public $fraudStatus;
    public $billerCode;
    public $billKey;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Order $order, $midtransTransactionId, $midtransTransactionTime, $paymentType, $vaNumber = null, $bank = null, $billerCoder = null, $billKey = null)
    {
        $this->order = $order;
        $this->midtransTransactionId = $midtransTransactionId;
        $this->midtransTransactionTime = $midtransTransactionTime;
        $this->paymentType = $paymentType;
        $this->vaNumber = $vaNumber;
        $this->bank = $bank;
        $this->billerCode = $billerCoder;
        $this->billKey = $billKey;
    }
}
