<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrashReport extends Model
{
    protected $table = 'crash_reports';

    protected $fillable = [
        'order_id',
        'photos',
        'notes'
    ];

    protected $casts = [
        'photos' => 'array'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
