<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Earning extends Model
{
    protected $table = 'earnings';

    protected $fillable = [
        'driver_id',
        'amount'
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
}
