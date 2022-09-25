<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderLocation extends Model
{
    protected $table = 'order_locations';

    protected $fillable = [
        'name', 'latitude', 'longitude', 'start', 'finish', 'order_id'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function scopeStartLocation($query)
    {
        return $query->where('start', true);
    }

    public function scopeFinishLocation($query)
    {
        return $query->where('finish', true);
    }
}
