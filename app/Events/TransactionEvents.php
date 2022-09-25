<?php

namespace App\Events;

/**
 * Midtrans Transaction status
 * @see https://api-docs.midtrans.com/#transaction-status
 */
class TransactionEvents
{
    public const PENDING = 'pending';

    public const SETTLEMENT = 'settlement';

    public const EXPIRE = 'expire';
}
