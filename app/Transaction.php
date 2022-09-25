<?php

namespace App;

use App\Traits\MidtransPayment;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use MidtransPayment;

    public const MENUNGGU_PEMBAYARAN = 'menunggu pembayaran';
    public const SUDAH_DIBAYAR = 'dibayar';
    public const MENUNGGU_KONFIRMASI = 'menunggu konfirmasi';
    public const KADALUARSA = 'kadaluarsa';

    protected $table = 'transactions';

    protected $fillable = [
        'order_id',
        'midtrans_transaction_id',
        'midtrans_transaction_time',
        'midtrans_snap_token',
        'status', //
        'payment_type',
        'va_number',
        'biller_code',
        'bill_key',
        'bank',
        'total_price',
        'payment_receipt'
    ];

    protected $appends = [
        'expired_transaction_time'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'midtrans_transaction_time' => 'datetime'
    ];

    public function getExpiredTransactionTimeAttribute()
    {
        return $this->midtrans_transaction_time ? $this->getExpiredPaymentDate($this->midtrans_transaction_time)->translatedFormat('l, j F Y, H:i') : null;
    }

    public function setStatusToWaitingPayment($snapToken)
    {
        $this->midtrans_snap_token = $snapToken;
        $this->status = self::MENUNGGU_PEMBAYARAN;

        return $this->save();
    }

    public function isPaymentPending()
    {
        return $this->status == self::MENUNGGU_PEMBAYARAN ? true : false;
    }

    public function isPaymentSettled()
    {
        return $this->status == self::SUDAH_DIBAYAR ? true : false;
    }

    public function isPaymentExpired()
    {
        return $this->status == self::KADALUARSA ? true : false;
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
