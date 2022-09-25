<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Panic extends Model
{
    protected $table = 'panics';

    protected $fillable = [
        'user_id', 'order_id',
        'latitude', 'longitude', 'location_name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
