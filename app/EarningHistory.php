<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EarningHistory extends Model
{
    public const INCOME_TYPE = 'income';
    public const OUTCOME_TYPE = 'outcome';

    protected $table = 'earning_histories';

    protected $fillable = [
        'driver_id',
        'periode_start',
        'periode_end',
        'type', // ['income', 'outcome']
        'amount',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
}
