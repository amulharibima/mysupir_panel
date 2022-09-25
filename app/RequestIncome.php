<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestIncome extends Model
{
    public const REQUESTED_STATUS = 'requested';
    public const TRANSFERRED = 'transferred';

    protected $table = 'request_incomes';

    protected $fillable = [
        'driver_id',
        'status',
        'nominal',
        'bank',
        'bank_account_number',
        'bank_account_holder'
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
}
